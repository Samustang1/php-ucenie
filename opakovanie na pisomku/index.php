<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
  // uvod, premenne, podmienky, cykly, polia

  // ÚLOHA 1
  /*
  Vytvorte cyklus od 50 do -20
  a vypíšte všetky čísla v postupnosti 0,5
  Výsledok: 50, 49,5, 49, 48,5 ... -19,5, -20
  */
  $cisla = [];
  for ($i = 50, $i>=-20, $i -=0,5) {
    echo $i . ", ";
  }
  

  // ÚLOHA 2
  /*
  Pole [4, 23, 56, 23, 5, 66, 12, 2]
  V cykle prejdite všetky prvky poľa a sčítajte
  všetky párne čísla a nepárne čísla zvlášť.
  Výsledok: 
  Súčet párnych: 4 + 56 + 66 + 12 + 2 = 140
  Súčet nepárnych: 23 + 23 + 5 = 51
  */
$nums = [4, 23, 56, 23, 5, 66, 12, 2];
$sucet_parnych = 0;
$sucet_neparnych = 0;

for ($i = 0; $i < 8; $i++) {
    if ($nums[$i] % 2 == 0) {
        $sucet_parnych += $nums[$i];
    } else {
        $sucet_neparnych += $nums[$i];
    }
}

echo "Súčet párnych: " . $sucet_parnych . "<br>";
echo "Súčet nepárnych: " . $sucet_neparnych;

  // ÚLOHA 3
  /*
  V cykle načítajte 10 náhodných čísel 
  v rozsahu od 0 do 1. 
  Pokiaľ je hodnota 0, vypíšte výsledok ako červenú bodku
  Pokiaľ je hodnota 1, vypíšte výsledok ako zelenú bodku
  Napr. vygenerované [0,1,1,0,1,0,1,0,0,0] 
  Výsledok: [•(R), •(G),•(G), •(R), ...]
  */
  $random_cisla = [];
  for($i = 0; $i<10; $i++) {
    $random_cislo = rand(o,1);
    if($random_cislo ==0) {
        echo "<span style = 'color:red'>•</span>";
    } else {
        echo "<span style = 'color:green'>+</span>";
    }
  }

  // ÚLOHA 4 (Trošku hard)
  /*
  Načítajte 4 čísla do poľa o 0 do 10. 
  Načítajte číslo premennej target (súčet možných čísel v poli)
  Vo výstupe by ste mali nájsť tú Nticu čísel, 
  ktorá vám tvorí hodnotu premennej target.

  Vstup: nums = [2,7,11,15], target = 9
  Výstup: [0,1]

  Vstup: nums = [3,2,4], target = 6
  Výstup: [1,2]
  */
  $nums = [2, 7, 11, 15];
  $target = 9;
  
  for ($i = 0; $i < 4; $i ++){
    for ($j = $i + 1; $j < 4; $j++) {
        if ($nums[$i] + $nums[$j] ==$targoet){
            echo "Vystup: [$i,$j]";
            break 2;
        }
    }
  }
    ?>
</body>
</html>