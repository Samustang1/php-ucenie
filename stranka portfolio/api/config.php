<?php
/**
 * Konfigurácia rezervačného formulára.
 * Upravte hodnoty podľa seba (najmä e-mail, na ktorý majú chodiť rezervácie).
 */

return [
    // Kam chodia rezervácie
    'recipient'   => 'cizsamko@gmail.com',

    // Odosielateľ. Ak máte vlastnú doménu, nastavte e-mail na nej (napr. no-reply@vasa-domena.sk).
    'from'        => 'cizsamko@gmail.com',

    // Predmet e-mailu
    'subject'     => 'Nová rezervácia z webu',

    // Zapnúť odoslanie e-mailu cez PHP mail()? Ak mail nefunguje, vypnite a použije sa len ukladanie do súboru.
    'enable_mail' => true,

    // Adresár, kam sa ukladajú všetky rezervácie (vytvorí sa automaticky)
    'save_dir'    => __DIR__ . '/../bookings',
];
