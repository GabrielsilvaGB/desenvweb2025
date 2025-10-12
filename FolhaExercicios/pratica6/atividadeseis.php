    <?php
$precos = [
    $_POST['preco1'],
    $_POST['preco2'],
    $_POST['preco3'],
    $_POST['preco4'],
    $_POST['preco5'],
    $_POST['preco6'],
];

$quilos = [
    $_POST['quilo1'],
    $_POST['quilo2'],
    $_POST['quilo3'],
    $_POST['quilo4'],
    $_POST['quilo5'],
    $_POST['quilo6'],
];

$produto = [
    "maçã",
    "melancia",
    "laranja",
    "repolho",
    "cenoura",
    "batatinha",
];

    $totalgeral = 0;

        for ($x = 0; $x < 6; $x++) {
            $totindividual = $precos[$x] * $quilos[$x];
            echo "O total do valor comprado em $produto[$x] é de R$ $totindividual <br>";
            $totalgeral += $totindividual;
        }

        echo "<br> O total da compra foi de R$ $totalgeral,00";

        if ($totalgeral > 50) {
            $totalmaior = $totalgeral - 50;
            echo "<br> <h3 style='color: red;'> Infelizmente o valor de R$50,00 não é suficiente, você gastou R$$totalmaior,00 a mais do que o esperado.</h3>";
        }
        elseif ($totalgeral < 50) {
            $totalmenor = 50 - $totalgeral;
            echo "<br> <h3 style='color: blue;'> O valor gasto nesta compra foi menor do que R$50,00, você ainda tem R$$totalmenor,00 para gastar.</h3>";
        }
        else {
            echo "<br> <h3 style='color: green;'> Você gastou exatamente R$50,00 o saldo para compras esgotou!</h3>";
        }
    ?>