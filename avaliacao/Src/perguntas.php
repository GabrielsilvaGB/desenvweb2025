<?php
session_start();

// bloqueia acesso se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/public/login.php");
    exit;
}

require_once __DIR__ . '/../src/conexao.php';
$pdo = getDbConnection();

$acao = $_GET['acao'] ?? null;
$id = $_GET['id'] ?? null;

// Criar pergunta
if ($acao === 'criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim($_POST['texto_pergunta']);

    if ($texto !== '') {
        $sql = "INSERT INTO perguntas (texto_pergunta, status) VALUES (:t, true)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', $texto);
        $stmt->execute();
    }

    header("Location: perguntas.php");
    exit;
}


// Editar pergunta
if ($acao === 'editar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $texto = trim($_POST['texto_pergunta']);

    if ($texto !== '') {
        $sql = "UPDATE perguntas SET texto_pergunta = :t WHERE id_pergunta = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', $texto);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    header("Location: perguntas.php");
    exit;
}


// Ativar / Inativar pergunta
if ($acao === 'toggle' && $id) {
    $sql = "UPDATE perguntas 
            SET status = NOT status 
            WHERE id_pergunta = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: perguntas.php");
    exit;
}


// Excluir pergunta
if ($acao === 'excluir' && $id) {
    $sql = "DELETE FROM perguntas WHERE id_pergunta = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: perguntas.php");
    exit;
}



$sql = "SELECT id_pergunta, texto_pergunta, status 
        FROM perguntas 
        ORDER BY id_pergunta ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Perguntas</title>
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
</head>

<body>

    <div class="admin-container">

        <div class="admin-header">
            <h1>Cadastro de Perguntas</h1>
            <a href="/avaliacao/public/admin.php" class="btn-secondary">Voltar</a>
        </div>

        <p class="small center">Gerencie as perguntas exibidas no tablet de avaliação.</p>

        <div class="card admin-form">
            <h3>Criar nova pergunta</h3>

            <form method="POST" action="perguntas.php?acao=criar">
                <label class="admin-label">Texto da Pergunta</label>
                <input type="text" name="texto_pergunta" class="admin-input" required>

                <button type="submit" class="btn">Salvar</button>
            </form>
        </div>


        <!-- Tabela de perguntas -->
        <div class="card">
            <h3>Perguntas Cadastradas</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pergunta</th>
                        <th>Status</th>
                        <th class="center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($perguntas as $p): ?>
                    <tr>
                        <td><?php echo $p['id_pergunta']; ?></td>
                        <td><?php echo htmlspecialchars($p['texto_pergunta']); ?></td>
                        <td>
                            <?php echo $p['status'] ? "<span class='status ativo'>Ativa</span>" 
                                                    : "<span class='status inativo'>Inativa</span>"; ?>
                        </td>

                        <td class="center">
                            <!-- Editar -->
                            <a class="btn-table edit" 
                            href="perguntas.php?acao=form_editar&id=<?php echo $p['id_pergunta']; ?>">
                            Editar
                            </a>

                            <!-- Ativar / Inativar -->
                            <a class="btn-table toggle" 
                            href="perguntas.php?acao=toggle&id=<?php echo $p['id_pergunta']; ?>">
                            <?php echo $p['status'] ? "Inativar" : "Ativar"; ?>
                            </a>

                            <!-- Excluir -->
                            <a class="btn-table delete"
                            href="perguntas.php?acao=excluir&id=<?php echo $p['id_pergunta']; ?>"
                            onclick="return confirm('Deseja realmente excluir esta pergunta?');">
                            Excluir
                            </a>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>



        <!-- Formulário de edição-->
        <?php if ($acao === 'form_editar' && $id): 

            $sql = "SELECT * FROM perguntas WHERE id_pergunta = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $editar = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($editar):
        ?>

        <div class="card admin-form highlight">
            <h3>Editar Pergunta #<?php echo $editar['id_pergunta']; ?></h3>

            <form method="POST" action="perguntas.php?acao=editar&id=<?php echo $editar['id_pergunta']; ?>">
                <label class="admin-label">Texto da Pergunta</label>
                <input type="text" name="texto_pergunta" value="<?php echo htmlspecialchars($editar['texto_pergunta']); ?>" class="admin-input" required>

                <button type="submit" class="btn">Salvar Alterações</button>
            </form>
        </div>
        <?php endif; endif; ?>
    </div>
</body>
</html>
