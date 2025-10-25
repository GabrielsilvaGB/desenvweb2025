<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$consulta = isset($_POST['cadastro']) ? trim($_POST['cadastro']) : '';

if ($consulta === '' || mb_strlen($consulta) < 2) {
    echo "<p>Informe pelo menos 2 caracteres para a busca.</p>";
    echo "<p><a href='atividadeum.html'>Voltar</a></p>";
    exit;
}

$conectionstring = "host=localhost 
                    port=5432 
                    dbname=local 
                    user=postgres 
                    password=1234";

$conection = pg_connect($conectionstring);

if (!$conection) {
    echo "<p>Erro na conexão com o banco de dados.</p>";
    exit;
}

$param = '%' . $consulta . '%';
$result = pg_query_params($conection,
    "SELECT pescodigo, pesnome, pessobrenome, pesemail, pescidade, pesestado FROM tbpessoa WHERE pesnome ILIKE $1 ORDER BY pescodigo",
    array($param)
);

if (!$result) {
    echo "<p>Erro na execução da consulta.</p>";
    exit;
}

if (pg_num_rows($result) == 0) {
    echo "<p>Não foi possível encontrar registros para: " . htmlspecialchars($consulta) . "</p>";
    echo "<p><a href='atividadeum.html'>Voltar</a></p>";
    exit;
}

echo "<h2>Resultado da busca por: " . htmlspecialchars($consulta) . "</h2>";
echo "<table border='1' cellpadding='5'>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Email</th>
            <th>Cidade</th>
            <th>Estado</th>
        </tr>";

while ($row = pg_fetch_assoc($result)) {
    $id = htmlspecialchars($row['pescodigo']);
    $nome = htmlspecialchars($row['pesnome']);
    $sobrenome = htmlspecialchars($row['pessobrenome']);
    $email = htmlspecialchars($row['pesemail']);
    $cidade = htmlspecialchars($row['pescidade']);
    $estado = htmlspecialchars($row['pesestado']);

    echo "<tr>
            <td>{$id}</td>
            <td>{$nome}</td>
            <td>{$sobrenome}</td>
            <td>{$email}</td>
            <td>{$cidade}</td>
            <td>{$estado}</td>
          </tr>";
}

echo "</table>";
echo "<p><a href='atividadeum.html'>Voltar</a></p>";
?>