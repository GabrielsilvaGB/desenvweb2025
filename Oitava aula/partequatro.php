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

    for ($x =1; $x <= 100; $x++){
        $salario1++;
        if ($x == 50){
            break;
        }
        
    }
    if ($salario1 > $salario2) {
     echo "Valor do Salario 1 = $salario1";
    }
    ?>
</body>
</html>
