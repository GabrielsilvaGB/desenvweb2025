<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $salario1 = 1000;
    $salario2 = 2000; 
   
    $salario1=$salario2; //Atribuindo salario 1 ao 2
    $salario2++; //Incrementando 1 ao salario 2
    $salario1 += $salario1 * 0.10; //Adicionando 10% ao salario 1

    if ($salario1 > $salario2) {
     echo "Salario 1 é maior que salario 2";
    }
    else if ($salario2 > $salario1){
        echo "salario 2 é maior que salario 1";
    }
    else {
        echo "Os valores são iguais";
    }
    ?>
</body>
</html>
