<?php
    $valoravista = 22500;
    $valorparcela = 489.65;
    $quantidadeparcelas = 60;

    $totalpago = $valorparcela * $quantidadeparcelas;

    $juros = $totalpago - $valoravista;

    echo "<h1>O valor dos juros pagos será de R$" . number_format($juros, 2, ',', '.') . "</h1>";
?>
