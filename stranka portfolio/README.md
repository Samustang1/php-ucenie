# LÁSKE Automotive Photography — portfólio + rezervačný formulár

Profesionálna jednostránková prezentácia pre automobilového fotografa.
Obsahuje galériu s filtrami a lightboxom, sekcie O mne / Služby / Proces / Recenzie / FAQ
a rezervačný formulár, ktorý odosiela dopyty na e-mail.

## Štruktúra

```
stranka portfolio/
├── index.html              # hlavná stránka
├── README.md               # tento návod
├── assets/
│   ├── css/style.css       # všetky štýly
│   └── js/main.js          # interakcie (menu, galéria, lightbox, formulár)
├── api/
│   ├── config.php          # ← tu nastavíte e-mail a možnosti
│   └── booking.php         # spracovanie formulára (uloží + pošle e-mail)
└── bookings/
    ├── .gitignore          # rezervácie sa necommitujú
    └── .htaccess           # zákaz priameho prístupu k rezerváciám
```

## 1) E-mail pre rezervácie

V súbore `api/config.php` je nastavené:

```php
'recipient'   => 'cizsamko@gmail.com',
'enable_mail' => true,
```

- `recipient` — kam majú chodiť rezervácie.
- Ak `mail()` na vašom hostingu nefunguje, nastavte `enable_mail => false`.
  Rezervácie sa aj tak uložia do `bookings/bookings-RRRR-MM.jsonl`.
- Na produkcii odporúčam napojiť SMTP (napr. cez PHPMailer) pre spoľahlivé doručenie.

## 2) Ako pridať vlastné fotky

V `index.html` nájdite sekciu `id="portfolio"`. Každá fotografia je jedna `<figure>`:

```html
<figure class="gallery__item reveal" data-category="exterior">
  <img src="CESTA/K/FOTKE.jpg" alt="Popis fotky" loading="lazy">
  <figcaption>
    <span class="gallery__cat">Exteriér</span>
    <span class="gallery__name">Názov projektu</span>
  </figcaption>
</figure>
```

- `data-category` určuje filter: `exterior`, `interior`, `detail`, `night`, `action`.
- Pre širokú fotku pridajte triedu `gallery__item--wide`.
- Fotky odporúčam vkladať do priečinka `assets/img/` a odkazovať relatívne, napr. `assets/img/auto-01.jpg`.
- Nahraďte aj obrázok v hero sekcii (`hero__media img`) a v sekcii O mne.

## 3) Ako spustiť (MAMP)

Projekt je v `C:\MAMP\htdocs\php-ucenie`. Ak chcete aj funkčný PHP formulár,
otvorte web cez MAMP (`http://localhost/...`), nie cez dvojklik na súbor.
Pri otvorení cez `file://` sa PHP nespustí a formulár použije záložné
riešenie — otvorí e-mailového klienta s vyplnenými údajmi.

## 4) Test formulára

```bash
curl -i -X POST http://localhost/php-ucenie/stranka%20portfolio/api/booking.php \
  -F "name=Ján Novák" \
  -F "email=jan@email.sk" \
  -F "message=Test rezervácie" \
  -F "gdpr=1"
```

Očakávaná odpoveď: `{"ok":true,"saved":true,"mailed":true}` (alebo `false`, ak mail nejde)
a nový riadok v `bookings/bookings-RRRR-MM.jsonl`.

## 5) Kontakty a texty

- E-mail a telefón sú v `index.html` (topbar, sekcia Rezervácia, pätička) a v `api/config.php`.
- Sociálne siete (Instagram, Facebook, YouTube) majú v `index.html` `href="#"` — nahraďte reálnymi odkazmi.
- Ceny v sekcii Služby upravte podľa seba.

## 6) Bezpečnosť

- `bookings/.htaccess` zakazuje priame stiahnutie uložených rezervácií.
- Formulár má honeypot pole proti spamu.
- Vstup sa čistí (`strip_tags`, limity dĺžky) pred uložením.

---

Vytvorené ako základ, ktorý môžete ľubovoľne rozširovať. Pri editácii štýlov
vychádzajte z premenných v `assets/css/style.css` (`:root`).
