<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$result = pg_query($conection, "SELECT pescodigo, pesnome, pessobrenome, pesemail, pescidade, pesestado FROM tbpessoa ORDER BY pescodigo");

if (!$result) {
    echo "<p>Erro na consulta SQL.</p>";
    exit;
}

echo "<h2>Lista de Pessoas</h2>";
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