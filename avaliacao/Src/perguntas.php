<?php
require_once 'conexao.php';

$query = "SELECT id_pergunta, texto_pergunta FROM perguntas ORDER BY id_pergunta";
$stmt = $conn->prepare($query);
$stmt->execute();
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($perguntas as $pergunta) {
    echo "<p class='pergunta'><strong>Pergunta {$pergunta['id_pergunta']}:</strong> {$pergunta['texto_pergunta']}</p>";
}
?>