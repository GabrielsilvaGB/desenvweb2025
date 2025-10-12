<?php
   $parcelas = $_POST['parcelas'];
   $valormoto = 8654.00;

   if ($parcelas == 24) {
       $taxa = 2.0;
   } elseif ($parcelas == 36) {
       $taxa = 2.3;
   } elseif ($parcelas == 48) {
       $taxa = 2.6;
   } elseif ($parcelas == 60) {
       $taxa = 2.9;
   }

   $montante = $valormoto * pow((1 + ($taxa / 100)), $parcelas);

   $valorparcela = $montante / $parcelas;

   echo "<h2>Simulação de financiamento de moto - Juros Compostos</h2><br>";
   echo "O valor da moto é de R$8.654,00<br>";
   echo "Número de parcelas: $parcelas vezes<br>";
   echo "Taxa de juros: $taxa% ao mês<br>";
   echo "Valor total a ser pago: R$" . number_format($montante, 2, ',', '.') . "<br>";
   echo "Valor de cada parcela: R$" . number_format($valorparcela, 2, ',', '.') . "<br>";
?>
