<?php
    $valor = $_REQUEST['valor'];
    $desconto = $_REQUEST['desconto'];

    function calculoDesconto($descontoInf, $valorInfo){
        if($descontoInf === 0 || $descontoInf > 100){
            echo "Não há desconto para ser aplicado :(";
        }
        else{
            $valorFinal = $valorInfo - ($valorInfo * $descontoInf);
            return $valorFinal; 
        }
    }

    $resultado = calculoDesconto($desconto, $valor);

    echo "DESCONTO = " . $desconto;
    echo "<br>";
    echo "VALOR = " . $valor;
    echo "<br>";
    echo "RESULTADO = " . $resultado;
?>