<?php
    $notas = array (8,4.9,9,6);
    $faltas = array(1,0,1,0,1,1,1,0,0,0);

    function medianotas($notas) {
        $soma =  array_sum($notas);
        $quantidade = count($notas);
        return $soma / $quantidade;
    }

    function statusnotas ($media){
        if ($media > 7) {
            return "Aprovado por nota";
        }
        else {
            return "Reprovado por nota";
        }
    }

$media = medianotas($notas);
$statusnotas = statusnotas($media);

echo "Média: ". number_format($media,2);
echo "<br>";
echo "Status: ". ($statusnotas);
?>