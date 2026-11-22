<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

<?php
// Polia

/*
Pole je premenná, ktorá si dokáže uchovať viacero hodnôt.
Každá hodnota v poli je indcexovaná. 

Indexovanie je jedinečné priradenie čísla k hodnote.
Indexovanie štandardne funguje od 0 po nekonečno.
(resp. po počet prvkov v našom poli)

Pole sa v programovaní (rôznych jazykoch) označuje
ako []. 
Napríklad $ovocie["pomaranč", "mandarinka", "slivka"]

Pole $ovocie obsahuje 3 hodnoty.
Index hodnoty pomaranč je 0.

V Jazyku PHP sa pole definuje pomocou funkcie array()

*/


    /*
    do while úloha:
    Vytvorte cyklus od 0 do 6.
    V každom cykle vytvorte kombináciu kariet. 
    Každý hráč dostane 2 karty. 

    Hodnoty:
    2 - 10, 11 (J), 12 (Q), 13 K, 14 (A)

    4 znaky - ♠ (čierna), ♥ (červená), ♣ (čierna), ♦ (červená)
    */
    $prvaKarta = 0;
    $druhaKarta = 0;
    $kartaJ = 'J'; // "Toto je string" 'J' (znak)
    $kartaQ = 'Q';
    $kartaK = 'K';
    $kartaA = 'A';

    $farba1 = "";
    $farba2 = "";

    $hodnotaKariet = 0;

    $znaky = array('♠','♥', '♣', '♦');

    for($i = 1; $i <= 6; $i++){
      $prvaKarta = rand(2, 14);
      $druhaKarta = rand(2, 14);

      if($hodnotaKariet < $prvaKarta + $druhaKarta){
        $hodnotaKariet = $prvaKarta + $druhaKarta;
        echo $hodnotaKariet;
      }
      // DOKONČIŤ

      $nahodnyZnak1 = rand(0, 3);
      $nahodnyZnak2 = rand(0, 3);

      if($nahodnyZnak1 == 1 || $nahodnyZnak1 == 3){
        $farba1 = "red";
      }else{
        $farba1 = "black";
      }

      if($nahodnyZnak2 == 1 || $nahodnyZnak2 == 3){
        $farba2 = "red";
      }else{
        $farba2 = "black";
      }

      if($prvaKarta == 11){
        $prvaKarta = $kartaJ;
      }elseif($prvaKarta == 12){
        $prvaKarta = $kartaQ;
      }elseif($prvaKarta == 13){
        $prvaKarta = $kartaK;
      }elseif($prvaKarta == 14){
        $prvaKarta = $kartaA;
      }
      if($druhaKarta == 11){
        $druhaKarta = $kartaJ;
      }elseif($druhaKarta == 12){
        $druhaKarta = $kartaQ;
      }elseif($druhaKarta == 13){
        $druhaKarta = $kartaK;
      }elseif($druhaKarta == 14){
        $druhaKarta = $kartaA;
      }
      echo "Hráč $i. " . "
      <span style = 'color: $farba1;'>{$prvaKarta}{$znaky[$nahodnyZnak1]}</span> 
      <span style = 'color: $farba2;'>{$druhaKarta}{$znaky[$nahodnyZnak2]}</span>" . "</br>";
      
      // Dobrovoľná domáca úloha :)
      // natavte výšku písma pre tie karty,
      // ktoré majú najvyšiu hodnotu 
      // PRO: šancu na výhru. 
    }
?>
</body>
</html>