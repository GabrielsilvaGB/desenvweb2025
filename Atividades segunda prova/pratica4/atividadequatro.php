<?php
    $v1 = $_GET['var1'];
    $v2 = $_GET['var2'];

    $calculo = $v1 * $v2;

    if ($calculo > 10) {
        echo "<h1>A área do retângulo de lados $v1 e $v2 metros é $calculo metros quadrados </h1>";
    }
    else{
        echo "<h3>A área do retângulo de lados $v1 e $v2 metros é $calculo metros quadrados </h3>";
    }
?>