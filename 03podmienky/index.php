<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    //podmienky
    /*
    Podmienka je programovacia paradigma pomocou ktorej vyhodnotocuje nejake hodnoty
    a tak potom zvoli ktory kod sa vykona
    Podmienky sa pouzivaju na rozhodovanie v programe. 
    Podmienka obsahuje logicke vyrazy, ktore sa vyhodnotia na true alebo false.
    */
    if (1 < 5) {
        echo "1 je menej ako 5";
    } 

    echo "<br>";

    $cislo = 10;
    $cislo2 = 50;

    if ($cislo != $cislo2) {
        echo "toto sa vykona";
    } 
     if ($cislo == $cislo2) {
        echo "toto sa vykona";
    } else {
        echo "toto sa nevykona";
    }
    ?>
</body>
</html>