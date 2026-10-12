<?php
/**
 * LÁSKE Automotive Photography — spracovanie rezervačného formulára.
 *
 * Prijíma POST (multipart/form-data alebo application/x-www-form-urlencoded),
 * validuje údaje, uloží rezerváciu do JSONL súboru a odošle e-mail.
 * Odpovedá vždy JSON-om: { "ok": true } alebo { "ok": false, "error": "..." }
 *
 * Kód je zámerne kompatibilný s PHP 5.5+ (funguje aj na PHP 7 a 8)
 * a nevyžaduje rozšírenie mbstring (ak chýba, použije záložné funkcie).
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Ak server nemá nastavenú časovú zónu, nastavíme slovenskú (odstráni warningy z date()).
if (!ini_get('date.timezone')) {
    @date_default_timezone_set('Europe/Bratislava');
}

// Povoliť len POST
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(array('ok' => false, 'error' => 'Povolená je len metóda POST.'), JSON_UNESCAPED_UNICODE);
    exit;
}

/** @var array $config */
$config = require __DIR__ . '/config.php';

/**
 * Bezpečne načíta jedno textové pole z POST.
 *
 * @param string $key
 * @return string
 */
function lask_field($key)
{
    if (!isset($_POST[$key])) {
        return '';
    }
    $value = $_POST[$key];
    if (is_array($value)) {
        return '';
    }
    return trim((string) $value);
}

/**
 * Dĺžka reťazca v znakoch (s ohľadom na UTF-8), aj bez mbstring.
 *
 * @param string $value
 * @return int
 */
function lask_strlen($value)
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value, 'UTF-8');
    }
    return strlen($value);
}

/**
 * Bezpečné skrátenie reťazca na maximálny počet znakov (UTF-8).
 *
 * @param string $value
 * @param int    $max
 * @return string
 */
function lask_substr($value, $max)
{
    $max = (int) $max;
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $max, 'UTF-8');
    }
    // Záložné riešenie bez mbstring — režeme po znakoch, aby sme nerozdelili UTF-8 znak.
    if (preg_match('//u', $value) === 1) {
        if (preg_match('/^.{0,' . $max . '}/us', $value, $m) === 1) {
            return $m[0];
        }
    }
    return substr($value, 0, $max);
}

/**
 * Očistí hodnotu pre uloženie.
 *
 * @param string $value
 * @param int    $max
 * @return string
 */
function lask_clean($value, $max = 2000)
{
    $value = strip_tags($value);
    $value = preg_replace('/[\r\n\t]+/', ' ', $value);
    return trim(lask_substr($value, $max));
}

// ---- Honeypot proti spamu ----------------------------------------------
// Pole "website" je skryté. Ak ho vyplní robot, tvárime sa, že všetko prešlo.
if (lask_field('website') !== '') {
    echo json_encode(array('ok' => true), JSON_UNESCAPED_UNICODE);
    exit;
}

// ---- Načítanie hodnôt ---------------------------------------------------
$name     = lask_field('name');
$email    = lask_field('email');
$phone    = lask_field('phone');
$car      = lask_field('car');
$date     = lask_field('date');
$time     = lask_field('time');
$location = lask_field('location');
$package  = lask_field('package');
$message  = lask_field('message');
$gdpr     = lask_field('gdpr');

// ---- Validácia ----------------------------------------------------------
$errors = array();

if (lask_strlen($name) < 2) {
    $errors['name'] = 'Prosím, vyplňte meno.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Prosím, zadajte platný e-mail.';
}
if (lask_strlen($message) < 5) {
    $errors['message'] = 'Napíšte prosím krátku správu.';
}
if ($gdpr === '') {
    $errors['gdpr'] = 'Potvrďte prosím súhlas so spracovaním údajov.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(
        array(
            'ok'     => false,
            'error'  => 'Skontrolujte prosím označené polia.',
            'fields' => $errors,
        ),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

// ---- Sanitizácia pre uloženie ------------------------------------------
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

$entry = array(
    'created_at' => date('c'),
    'ip'         => $ip,
    'name'       => lask_clean($name, 120),
    'email'      => lask_clean($email, 160),
    'phone'      => lask_clean($phone, 60),
    'car'        => lask_clean($car, 120),
    'date'       => lask_clean($date, 30),
    'time'       => lask_clean($time, 20),
    'location'   => lask_clean($location, 160),
    'package'    => lask_clean($package, 60),
    'message'    => trim(lask_substr(strip_tags($message), 4000)),
);

// ---- Uloženie do JSONL súboru (bezpečné voči súbehu) --------------------
$saved = false;
$dir   = (string) $config['save_dir'];

if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
}

if (is_dir($dir) && is_writable($dir)) {
    $file = $dir . '/bookings-' . date('Y-m') . '.jsonl';
    $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    $saved = @file_put_contents($file, $line, FILE_APPEND | LOCK_EX) !== false;
}

// ---- E-mail (voliteľný) -------------------------------------------------
$mailed = false;
if (!empty($config['enable_mail'])) {
    $lines = array(
        'Nová rezervácia z webu LÁSKE Automotive',
        str_repeat('-', 42),
        'Meno:     ' . $entry['name'],
        'E-mail:   ' . $entry['email'],
        'Telefón:  ' . $entry['phone'],
        'Vozidlo:  ' . $entry['car'],
        'Dátum:    ' . $entry['date'] . ' ' . $entry['time'],
        'Lokalita: ' . $entry['location'],
        'Balík:    ' . $entry['package'],
        '',
        'Správa:',
        $entry['message'],
        '',
        'Odoslané: ' . $entry['created_at'],
    );
    $body = implode("\r\n", $lines);

    $headers = implode("\r\n", array(
        'From: ' . $config['from'],
        'Reply-To: ' . $entry['email'],
        'Content-Type: text/plain; charset=UTF-8',
        'MIME-Version: 1.0',
    ));

    $mailed = @mail(
        (string) $config['recipient'],
        '=?UTF-8?B?' . base64_encode((string) $config['subject']) . '?=',
        $body,
        $headers
    );
}

// ---- Odpoveď ------------------------------------------------------------
// Ak sa nepodarilo uložiť ani odoslať, vrátime chybu.
if (!$saved && !$mailed) {
    http_response_code(500);
    echo json_encode(
        array(
            'ok'    => false,
            'error' => 'Rezerváciu sa nepodarilo spracovať. Skúste to prosím neskôr alebo napíšte e-mail.',
        ),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

echo json_encode(array('ok' => true, 'saved' => $saved, 'mailed' => $mailed), JSON_UNESCAPED_UNICODE);
