<?php
   $parcelas = $_POST['parcelas'];
   $valormoto = 8654.00;

   if ($parcelas == 24) {
    $taxa = 1.5;
   }
    elseif ($parcelas == 36) {
     $taxa = 2.0;
    }
    elseif ($parcelas == 48) {
     $taxa = 2.5;
    }
    elseif ($parcelas == 60) {
     $taxa = 3.0;
    }

    $montante = $valormoto * (1 + ($taxa / 100) * $parcelas);
    $valorparcela = $montante / $parcelas;
    echo "<h2>Simulação de financiamento de moto</h2> <br>";
    echo "O valor da moto é de R$8.654,00<br>";
    echo "O número de parcelas escolhido foi de $parcelas meses<br>";
    echo "A taxa de juros aplicada foi de $taxa% ao mês<br>";
    echo "O valor total a ser pago será de R$". number_format($montante, 2, ',', '.') ."<br>";
    echo "O valor de cada parcela será de R$". number_format($valorparcela, 2, ',', '.') ."<br>";
?>