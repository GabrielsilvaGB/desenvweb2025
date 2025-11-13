<?php
function getDbConnection() {
    $host = "localhost";
    $porta = "5432";
    $banco = "avaliacao_cliente";
    $usuario = "postgres";
    $senha = "1234";

    try {
        $conn = new PDO("pgsql:host=$host;port=$porta;dbname=$banco", $usuario, $senha);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}
?>