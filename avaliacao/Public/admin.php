<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/src/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
</head>

<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Painel Administrativo</h1>
        </div>
        <p class="small center">Gerencie perguntas, dispositivos e visualize o desempenho das avaliações.</p>

        <div class="admin-menu">
            <a href="/avaliacao/src/perguntas.php" class="menu-item">
                <h4>Cadastro de Perguntas</h4>
                <p class="small">Criar, editar, ativar e remover perguntas</p>
            </a>

            <a href="/avaliacao/public/dispositivos.php" class="menu-item">
                <h4>Cadastro de Dispositivos</h4>
                <p class="small">Gerenciar tablets e setores avaliados</p>
            </a>

            <a href="/avaliacao/public/dashboard.php" class="menu-item">
                <h4>Dashboard</h4>
                <p class="small">Gráficos, médias e estatísticas gerais</p>
            </a>

            <a href="/avaliacao/public/avaliacoes.php" class="menu-item">
                <h4>Visualizar Avaliações</h4>
                <p class="small">Listagem completa das respostas</p>
            </a>

            <a href="/avaliacao/public/index.php" class="menu-item">
                <h4>Ir para Tela de Avaliação</h4>
                <p class="small">Voltar para a parte do tablet</p>
            </a>
        </div>

        <div class="admin-actions">
            <a href="/avaliacao/src/logout.php" class="btn-secondary">Logout</a>
        </div>
    </div>

</body>
</html>

