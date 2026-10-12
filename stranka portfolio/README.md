# LÁSKE — Automobilové fotoportfólio

Web pre automobilového fotografa: viacstránkové portfólio s galériou a rezervačným
formulárom. Svetlý „magazínový" dizajn (papier, serif Fraunces, hairline linky).

> Stará jednostranová verzia je v archíve: `archiv/portfolio-v1/` (nie je súčasťou live webu).

## Štruktúra

```
stranka portfolio/
├─ index.html        Domov (hero, výber prác, o mne, služby, CTA)
├─ portfolio.html    Galéria s filtrami a lightboxom
├─ o-mne.html        O mne
├─ sluzby.html       Služby + časté otázky
├─ kontakt.html      Kontakt a rezervačný formulár
├─ dakujeme.html     Poďakovanie po odoslaní (fallback pre formulár)
└─ assets/
   ├─ css/style.css  Štýly (premenné v :root)
   └─ js/main.js     Menu, reveal, filtre, lightbox, formulár
```

## Rezervačný formulár

Formulár odosiela e-mail cez službu **FormSubmit** priamo do schránky
`cizsamko@gmail.com`. Návštevník **neopúšťa stránku** a nepotrebuje e-mailového
klienta (žiadny `mailto`). Prvýkrát treba formulár jednorazovo **aktivovať** —
po prvom odoslaní príde na `cizsamko@gmail.com` e-mail od FormSubmit s odkazom
„Activate Form"; stačí naň kliknúť (skontroluj aj spam).

Adresu príjemcu meníš na dvoch miestach:

- `kontakt.html` — atribút `action` formulára a `_next` (ďakovacia stránka)
- `assets/js/main.js` — konštanta `ENDPOINT` v časti „Rezervačný formulár"

## Spustenie (MAMP)

Projekt je v `C:\MAMP\htdocs\php-ucenie`. Otvor
`http://localhost/php-ucenie/stranka%20portfolio/` (nie cez dvojklik na súbor).

## Fotky a texty

- Fotky sú načítané z Unsplash s fallbackom na picsum. Vlastné fotky vlož do
  `assets/img/` a nahraď `src` v HTML.
- E-mail a telefón sú v `kontakt.html` a v pätičke každej stránky.
  Telefón `+421 000 000 000` je zatiaľ placeholder — nahraď reálnym číslom.
- Sociálne siete majú `href` na `instagram.com` — nahraď svojím profilom.

## Nasadenie

Push do vetvy `main` spustí GitHub Actions workflow
(`.github/workflows/deploy-pages.yml`), ktorý nasadí priečinok
`stranka portfolio` na GitHub Pages.
