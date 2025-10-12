<?php
    $v1 = $_GET['var1'];

    if ($v1 % 2 == 0) {
        echo "<h2>Valor divisível por 2!</h2>";
    }
    else {
        echo "<h2>O valor não é divisível por 2!</h2>";
    }
?>