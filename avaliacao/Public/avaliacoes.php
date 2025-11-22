<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/src/login.php");
    exit;
}

require_once __DIR__ . '/../src/conexao.php';
$conn = getDbConnection();

$avaliacoes = $conn->query("
    SELECT 
        a.id_avaliacao, 
        a.resposta, 
        a.feedback_textual, 
        a.data_hora, 
        p.texto_pergunta,
        s.nome as setor_nome
    FROM avaliacoes a
    INNER JOIN perguntas p ON p.id_pergunta = a.id_pergunta
    LEFT JOIN setores s ON s.id_setor = a.id_setor
    ORDER BY a.data_hora DESC
")->fetchAll(PDO::FETCH_ASSOC);

function esc($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Avaliações</title>
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
</head>
<body>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Avaliações Registradas</h1>
            <a href="/avaliacao/public/admin.php" class="btn-secondary">Voltar</a>
        </div>

        <p class="small center">Lista completa de todas as avaliações realizadas.</p>

        <div class="card">
            <h3>Avaliações</h3>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pergunta</th>
                            <th>Setor</th>
                            <th>Nota</th>
                            <th>Feedback</th>
                            <th>Data / Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($avaliacoes)): ?>
                            <tr><td colspan="6" class="center small">Nenhuma avaliação registrada.</td></tr>
                        <?php else: ?>
                            <?php foreach ($avaliacoes as $a): ?>
                                <tr>
                                    <td><?= (int)$a['id_avaliacao'] ?></td>
                                    <td><?= esc($a['texto_pergunta']) ?></td>
                                    <td><?= esc($a['setor_nome'] ?? '—') ?></td>
                                    <td><?= esc($a['resposta']) ?></td>
                                    <td><?= esc($a['feedback_textual']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($a['data_hora'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>