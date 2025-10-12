<?php
    $v1 = $_POST['var1'];
    $v2 = $_POST['var2'];
    $v3 = $_POST['var3'];

    $soma = $v1 + $v2 + $v3;

    if ($v1 > 10) {
        echo "<h2 style='color: blue;'>A soma dos valores é: $soma</h2>";     
    }
    elseif ($v2 < $v3) {
        echo "<h2 style='color: green;'>A soma dos valores é $soma</h2>";
    }
    elseif ($v3 < $v1 && $v3 < $v2){
        echo "<h2 style='color: red;'>A soma dos valores é: $soma</h2>";
    }
    else {
        echo "<h2> A soma dos valores é: $soma</h2>";
    }
?>