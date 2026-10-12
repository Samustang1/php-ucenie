< !DOCTYPE html >
< html lang="en" >
< head >
< meta charset="UTF-8" >
< meta name="viewport" content="width=device-width, initial-scale=1.0" >
< title >Document< /title >
< /head >
< body >
< ?php
echo "< h1 >Tento web je zamerany na premenne< /h1 >";
? >

< ?php
echo "< p > . 2+ 4 .< /p >";

//Priradenie hodnoty do premennej
$cislo = 5;

//Vypisanie hodnoty premennej
echo $cislo;

echo "< br >";
$desCislo = 4.2;
echo $desCislo;

echo "< br >";

$cislo1 = 4.3;
$cislo2 = 2.7;

$vysledok = (int)$cislo1 + (int)$cislo2;
echo $vysledok;

$text = "toto je moj text";
echo $text;
echo "< br >";

$textCislo = "toto je moje cislo " . $cislo1;
echo $textCislo;

echo "< br >";
$pravdivost = true;
echo $pravdivost;
? >
< /body >
< /html >