
<?php
session_start();

// Segurança: só acessa se estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location:  /avaliacao/src/login.php");
    exit;
}

require_once "../src/conexao.php";
$conn = getDbConnection();


// PROCESSAR AÇÕES (add / edit / delete)
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Adicionar setor
    if (isset($_POST['acao']) && $_POST['acao'] === "add") {
        $nome = trim($_POST['nome_setor']);
        if ($nome !== "") {
            $stmt = $conn->prepare("INSERT INTO setores (nome_setor, status) VALUES (:nome, TRUE)");
            $stmt->execute([':nome' => $nome]);
            $mensagem = "Setor cadastrado com sucesso!";
        }
    }

    // Editar setor
    if (isset($_POST['acao']) && $_POST['acao'] === "edit") {
        $id = (int)$_POST['id_setor'];
        $nome = trim($_POST['nome_setor']);
        $status = isset($_POST['status']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE setores SET nome_setor = :nome, status = :status WHERE id_setor = :id");
        $stmt->execute([
            ':nome' => $nome,
            ':status' => $status,
            ':id' => $id
        ]);

        $mensagem = "Setor atualizado com sucesso!";
    }
}

// excluir setor
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM setores WHERE id_setor = :id");
    $stmt->execute([':id' => $id]);
    $mensagem = "Setor excluído!";
}


// listar setores
$setores = $conn->query("SELECT * FROM setores ORDER BY id_setor ASC")->fetchAll(PDO::FETCH_ASSOC);


$editar = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM setores WHERE id_setor = :id");
    $stmt->execute([':id' => $id]);
    $editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Setores</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Gerenciar Setores</h1>
            <a href="admin.php" class="btn-secondary">Voltar</a>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-success"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <!-- Formulario de adicionar / editar -->
        <div class="form-card">

            <?php if ($editar): ?>
                <h3>Editando Setor</h3>
                <form method="POST">

                    <input type="hidden" name="acao" value="edit">
                    <input type="hidden" name="id_setor" value="<?php echo $editar['id_setor']; ?>">

                    <label>Nome do Setor:</label>
                    <input type="text" name="nome_setor" required
                        value="<?php echo htmlspecialchars($editar['nome_setor']); ?>">

                    <label>Status:</label><br>
                    <input type="checkbox" name="status" <?php echo $editar['status'] ? 'checked' : ''; ?>>
                    Ativo

                    <div class="form-actions">
                        <button class="btn-primary">Salvar Alterações</button>
                        <a href="setores.php" class="btn-secondary">Cancelar</a>
                    </div>

                </form>

            <?php else: ?>

                <h3>Novo Setor</h3>
                <form method="POST">
                    <input type="hidden" name="acao" value="add">

                    <label>Nome do Setor:</label>
                    <input type="text" name="nome_setor" required placeholder="Ex.: Recepção">

                    <button class="btn-primary">Cadastrar</button>
                </form>

            <?php endif; ?>

        </div>

        <!-- Tabela de setores -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Setor</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($setores as $setor): ?>
                    <tr>
                        <td><?php echo $setor['id_setor']; ?></td>
                        <td><?php echo htmlspecialchars($setor['nome_setor']); ?></td>
                        <td>
                        <span class="status-pill <?php echo $setor['status'] ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $setor['status'] ? 'Ativo' : 'Inativo'; ?>
                        </span>
                        </td>
                        <td class="table-actions">
                            <a href="setores.php?edit=<?php echo $setor['id_setor']; ?>" class="btn-edit">Editar</a>
                            <a href="setores.php?delete=<?php echo $setor['id_setor']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Tem certeza que deseja excluir este setor?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

</body>
</html>
