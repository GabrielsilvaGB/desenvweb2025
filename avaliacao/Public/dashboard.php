<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/public/login.php");
    exit;
}

require_once __DIR__ . '/../src/conexao.php';
$conn = getDbConnection();

// Total de avaliações
$totalAvaliacoes = $conn->query("
    SELECT COUNT(*) AS total 
    FROM avaliacoes
    ")->fetch(PDO::FETCH_ASSOC)['total'];

// Média geral
$mediaGeral = $conn->query("
    SELECT AVG(resposta) AS media 
    FROM avaliacoes
    ")->fetch(PDO::FETCH_ASSOC)['media'];
$mediaGeral = number_format($mediaGeral, 2, ',', '.');

// Média por pergunta
$mediasPerguntas = $conn->query("
    SELECT p.texto_pergunta, AVG(a.resposta) AS media
    FROM avaliacoes a
    INNER JOIN perguntas p ON p.id_pergunta = a.id_pergunta
    GROUP BY p.id_pergunta, p.texto_pergunta
    ORDER BY p.id_pergunta
")->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
</head>
<body>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <a href="/avaliacao/public/admin.php" class="btn-secondary">Voltar</a>
        </div>

        <div class="grid">
            <div class="card">
                <strong>Total de Avaliações</strong>
                <p><strong><?= $totalAvaliacoes ?></strong></p>
            </div>

            <div class="card">
                <strong>Média Geral</strong>
                <p><strong><?= $mediaGeral ?></strong></p>
            </div>
        </div>

        <div class="card">
            <strong>Média por Pergunta</strong>
            <ul>
                <?php foreach ($mediasPerguntas as $m): ?>
                    <li>
                        <?= htmlspecialchars($m['texto_pergunta']) ?>: <strong><?= number_format($m['media'], 2, ',', '.') ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>  
</body>
</html>
