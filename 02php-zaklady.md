---
url: /ssosta/notes/2r/pit/02php-zaklady.md
description: Základy PHP, jeho syntax, premenné, operátory, funkcie a riadiace štruktúry.
---

# 2. Základy PHP

:::warning Poznámka
Poznámky pána učiteľa nájdete v [GitHub repozitári](https://github.com/MarosMincak/PHP-poznamky).
:::

## Čo je to PHP?

* Skriptovací jazyk na strane PHP
* Dynamické webové stránky
* Kód sa spúšťa na serveri

:::tip

PHP je skratka pre "PHP: Hypertext Preprocessor" (originálne to bolo "Personal Home Page")

:::

## Základná syntax

* Komentáre:
  ```php
  // jednoriadkovy komentar
  /* viacriadkovy
     komentar
  */
  ```
* PHP kód sa zapisuje medzi značky
  ```php
  <?php ... ?>
  ```
* Každý príkaz sa ukončuje znakom `;`
* Každá premenná má pred jej názvom znak `$`, musí začínať písmenom, je case-sensitive
  ```php
  $meno = "Juraj";
  $1meno = "Juraj"; // error // [!code error]
  $Meno != $meno;
  ```
* Spájanie reťazcov pomocou `.` (bodka)
  ```php
  $pozdrav = "Ahoj, " . $meno;
  ```
* Spájanie reťazcov pomocou interpolácie (iba v dvojitých úvodzovkách)
  ```php
  $pozdrav = "Ahoj, $meno";
  // alebo
  $pozdrav = "Ahoj, {$meno}";
  ```
* Výpis:
  | | `echo` | `print` |
  |------------------|-----------------------------------------------------------|-----------------------------------------|
  | **Popis** | Vypisuje hodnotu na výstup.Je o niečo rýchlejšie ako `print`. | Vypisuje hodnotu na výstup.Dá sa použiť vo výrazoch. |
  | **Návratová hodnota** | nemá | `1` |
  | **Argumenty** | viacero (oddelených čiarkou) | iba jeden |
  | **Príklad** | `echo "Ahoj, ", $meno, "!";` | `print "Ahoj, " . $meno . "!";` |
  * `var_dump` - detailný výpis typu a hodnoty
  * `print_r` - čitateľný výpis hodnôt (pre zložitejšie štruktúry), menej detailný ako `var_dump`

## Operátory

* Aritmetické: `+`, `-`, `*`, `/`, `%`, `**`
* Porovnávacie: `==`, `===` (kontroluje aj typ), `!=`, `!==`, `<`, `>`, `<=`, `>=`
* Logické: `and`, `or`, `xor`, `&&`, `||`, `!`
* Priraďovacie: `=`, `+=`, `-=`, `*=`, `/=`, `%=`, `.=`

### Príklad

```php
<?php
$cislo1 = 10;
$cislo2 = 20;
$sucet = $cislo1 + $cislo2;
echo "Súčet je: " . $sucet;
?>
```

## Funkcia

* Blok kódu, ktorý môžeme znovu kedykoľvek použiť
* Výhody:
  * Znovupoužiteľnosť kódu => kód sa neopakuje
  * Zlepšenie čitateľnosti kódu
* Parametre a návratové hodnoty
  * Parametre: vstupné hodnoty, ktoré funkcia potrebuje na vykonanie svojej úlohy
  * Argumenty: konkrétne hodnoty, ktoré odovzdávame funkcii pri jej volaní
  * Návratová hodnota: výsledok, ktorý funkcia vráti po vykonaní

::: code-group

```php [Funkcia bez parametrov a návratovej hodnoty]
function pozdrav() {
  // telo funkcie
  echo "Ahoj!";
}
pozdrav(); // volanie funkcie
```

```php [Funkcia s parametrami]
function pozdravMeno($meno) {
  // $meno je parameter
  echo "Ahoj, " . $meno . "!";
}
pozdravMeno("Juraj"); // Ahoj, Juraj!
// "Juraj" je argument
```

```php [Funkcia s návratovou hodnotou]
function sucet($a, $b) {
  // $a a $b sú parametre
  return $a + $b;
}
$vysledok = sucet(5, 10);
// 5 a 10 sú argumenty
echo "Súčet je: " . $vysledok; // 15
```

:::

## Typy premenných

### Konštanty

* Hodnoty, ktoré sa nemenia

```php
define('SCHOOL', 'Stredná škola ABC');
echo 'Konštanta SCHOOL = ' . SCHOOL . "<br>";
```

### Základné typy

* **Integer**: celé číslo
* **Float**: desatinné číslo
* **String**: reťazec
* **Boolean**: pravda / nepravda
* **Null**: žiadna hodnota

```php
$cislo = 5; // Integer
$desatinne = 5.5; // Float
$retezec = "Ahoj"; // String
$pravda = true; // Boolean
$nic = null; // Null
```

### Konverzia typov

* Explicitné pretypovanie

```php
$cislo = "10"; // String
$cisloInt = (int)$cislo; // Konverzia na Integer
$cisloFloat = (float)$cislo; // Konverzia na Float
$cisloStr = (string)$cisloInt; // Konverzia na String

echo var_dump($cisloInt); // zobrazí typ a hodnotu
```

### Polia

* Umožňujú uložiť viacero hodnôt do jednej premennej

* Indexované polia (indexujeme od 0)

  ```php
  $zelenina = ["mrkva", "zemiak", "uhorka"];
  echo $zelenina[1]; // zemiak

  $ovocie = array("jablko", "banan", "cerešňa");
  echo $ovocie[0]; // jablko
  ```

* Asociatívne polia
  ```php
  $osoba = [
      "meno" => "Juraj",
      "vek" => 30,
      "mesto" => "Bratislava"
  ];
  echo $osoba["meno"]; // Juraj
  ```

- Viacdimenziálne polia
  ```php
  $studenti = [
      ["meno"=>"Anna", "vek"=>20, "znamky"=>[1, 2, 3]],
      ["meno"=>"Boris", "vek"=>22, "znamky"=>[4, 5, 6]],
      ["meno"=>"Cyril", "vek"=>21, "znamky"=>[7, 8, 9]]
  ];
  echo $studenti[1]["znamky"][2]; // 6
  ```
- Práca s poľami
  * Pridanie prvku na koniec poľa:
    ```php
    $pole[] = "novy_prvok";
    array_push($pole, "novy_prvok");
    ```
  * Odstránenie posledného prvku poľa:
    ```php
    array_pop($pole);
    ```
  * Úprava prvku:
    ```php
    $pole[index] = "nova_hodnota";
    $pole[index][subindex] = "nova_hodnota"; // pre viacdimenzionálne pole
    ```
  * Počet prvkov:
    ```php
    count($pole);
    ```
  * Zoradenie vzostupne (abecedne):
    ```php
    sort($pole); // mení pôvodné pole
    ```
  * Kontrola, či hodnota existuje v poli:
    ```php
    in_array("hladany_prvok", $pole);
    ```

## Podmienky

* výraz, ktorý sa vyhodnotí na true alebo false
* základný formát:

```php
if (podmienka) {
    // vykoná sa, ak je podmienka pravdivá
} elseif (ina_podmienka) {
    // vykoná sa, ak prvá neplatí a táto je pravdivá
} else {
    // vykoná sa, ak nič vyššie neplatí
}
```

::: code-group

```php [Jednoduché porovnanie]
if (10 < 20) {
  echo "10 je menej ako 20<br>";
} else {
  echo "10 je viac alebo rovné 20<br>";
}
```

```php [Pravdivosť čísel v podmienke]
$cislo = 5;
if ($cislo) { // 0 je „nepravda“, iné čísla „pravda“
  echo "Premenná \$cislo má hodnotu (nie je 0 ani null)<br>";
}
```

```php [if / elseif / else s náhodným číslom]
$nahodne_cislo = rand(0, 10); // celé číslo 0 až 10
echo "Náhodné číslo: $nahodne_cislo<br>";

if ($nahodne_cislo > 5) {
  echo "Číslo je > 5<br>";
} elseif ($nahodne_cislo == 5) {
  echo "Číslo je == 5<br>";
} else {
  echo "Číslo je < 5 alebo 0–4<br>";
}
```

:::

## Cykly

* Časť kódu, ktorá sa opakuje, kým platí určitá podmienka
* Pomáha nám vykonať rovnakú činnosť viackrát bez zbytočného opakovania kódu.

### FOR

* Používa sa, keď vieme, koľkokrát sa má cyklus opakovať
* Štruktúra: `for (začiatok; podmienka; krok) { ... }`

```php
// Vypíše čísla 0 až 4
for ($i = 0; $i < 5; $i++) {
    echo "Číslo: $i<br>";
}
```

### WHILE

* Vykonáva sa dovtedy, kým platí podmienka.
* Najprv sa overí podmienka, potom sa kód vykoná.

```php
// Vypíš všetky párne čísla od 0 do 10
$i = 0;
while ($i <= 10) {
  if ($i % 2 == 0) {
    echo "Párne číslo: $i<br>";
  }
  $i++;
}
// Vypíše: 0, 2, 4, 6, 8, 10
```

### DO...WHILE

* Najprv sa vykoná telo cyklu a až potom sa kontroluje podmienka.
* Cyklus sa vykoná aspoň raz.

```php
// Opýta sa používateľa na číslo a vypíše všetky čísla od 1 po toto číslo (aspoň raz, aj keď zadá 0)
$max = 0; // napr. simulujeme vstup používateľa
do {
  echo "Číslo: $max<br>";
  $max++;
} while ($max <= 5);
// Vypíše čísla 0 až 5, aj keď pôvodne bolo $max = 0
```

### FOREACH

* Používa sa na prechádzanie (čítanie) všetkých prvkov poľa.

```php
$ovocie = ["jablko", "banan", "cerešňa"];
foreach ($ovocie as $item) {
    echo $item . "<br>";
}
// Vypíše všetko ovocie v poli
```

## Cvičenia

### Podmienky

* Vygeneruj 3 čísla v rozsahu 1–3 (vrátane).
* Do premennej $guess si daj vlastný tip (tiež tri čísla 1–3).
* Ak sa tvoj tip presne zhoduje s vygenerovanými číslami v rovnakom poradí, vypíš "Super!".
* Inak vypíš "Niektoré číslo je iné".

:::tip Poznámka
Použijeme pole a porovnáme ho s == (stačí nám porovnanie hodnôt a poradia).
:::

:::details Riešenie

```php
$cislo1 = rand(1, 3);
$cislo2 = rand(1, 3);
$cislo3 = rand(1, 3);

$generovane = [$cislo1, $cislo2, $cislo3];

$guess = [1, 2, 3]; // napr. zmeň na svoje čísla 1–3

echo "Vygenerované: [" . $generovane[0] . ", " . $generovane[1] . ", " . $generovane[2] . "]<br>";
echo "Tvoj tip:     [" . $guess[0] . ", " . $guess[1] . ", " . $guess[2] . "]<br>";

if ($generovane == $guess) {
  echo "Super!<br>";
} else {
  echo "Niektoré číslo je iné.<br>";
}
```

:::

### Cykly

#### Zadanie 1

* Použi cyklus for, ktorý prejde čísla od 1 do 10.
* Pomocou if zisti, ktoré čísla sú párne a ktoré nepárne.
* Každé číslo vypíš na nový riadok so slovom „párne“ alebo „nepárne“.

:::details Riešenie

```php
for ($i = 1; $i <= 10; $i++) {
  if ($i % 2 == 0) {
    echo "$i je párne<br>";
  } else {
    echo "$i je nepárne<br>";
  }
}
```

:::

#### Zadanie 2

* Pomocou while cyklu vypočítaj súčet všetkých čísel od 1 po 100
  a nakoniec vypíš výsledok.

:::details Riešenie

```php
$sum = 0;
$i = 1;
while ($i <= 100) {
  $sum += $i; // sum = sum + i
  $i++;
}
echo "Súčet čísel od 1 do 100 je: $sum<br>";
```

:::

### Funkcie

#### Zadanie 1

* Vytvor funkciu, ktorá vypíše všetky čísla, ktoré sú deliteľné tromi od 1 do 20.

:::details Riešenie

```php
function delitelneTromi() {
  for ($i = 1; $i <= 20; $i++) {
    if ($i % 3 == 0) {
      echo "$i<br>";
    }
  }
}
delitelneTromi();
```

:::

#### Zadanie 2

* Vytvor funkciu s názvom skontrolujCislo($cislo), ktorá vypíše „pozitívne“, „negatívne“ alebo „nula“ podľa zadanej hodnoty.

:::details Riešenie

```php
function skontrolujCislo($cislo) {
  if ($cislo > 0) {
    echo "pozitívne<br>";
  } elseif ($cislo < 0) {
    echo "negatívne<br>";
  } else {
    echo "nula<br>";
  }
}
skontrolujCislo(5);   // pozitívne
skontrolujCislo(-3);  // negatívne
skontrolujCislo(0);   // nula
```

:::

### Polia

#### Zadanie 1

* Funkcia, ktorá vypočíta priemer hodnôt v poli (očakávame 10 náhodných čísel)

:::details Riešenie

```php
function priemerPola($pole) {
  $suma = 0;
  $pocet = count($pole);
  foreach ($pole as $hodnota) {
    $suma += $hodnota;
  }
  return $suma / $pocet;
}
$cisla = [];
for ($i = 0; $i < 10; $i++) {
  $cisla[] = rand(1, 100);
}
echo "Priemer: " . priemerPola($cisla) . "<br>";
```

:::
