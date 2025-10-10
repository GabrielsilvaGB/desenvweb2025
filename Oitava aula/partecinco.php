<?php

$disciplinas = array(
    "Estrutura de dados 2",
    "Engenharia de software 2",
    "Admisntração e sistemas de informação ",
    "Programação web 1","Banco de dados 2"
);

$professores = array(
    "Fernando Bastos",
    "Julian","Marciel",
    "Cleber","Marcos"
);

for ($i = 0; $i <5; $i++){
  echo "Disciplina:". $disciplinas[$i].", professor:".$professores[$i];
  echo "<br>";
  
}
?>




