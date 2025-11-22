<?php
session_start();

// bloqueia acesso se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/src/login.php");
    exit;
}

require_once __DIR__ . '/../src/conexao.php';
$pdo = getDbConnection();

// Obtém ações e IDs
$acao = $_GET['acao'] ?? null;
$id = $_GET['id'] ?? null;
$filtro_setor_id = isset($_GET['setor']) ? (int)$_GET['setor'] : null;

$sql_setores = "SELECT id_setor, nome FROM setores ORDER BY nome ASC";
$stmt_setores = $pdo->query($sql_setores);
$setores = $stmt_setores->fetchAll(PDO::FETCH_ASSOC);


// Criar pergunta
if ($acao === 'criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim($_POST['texto_pergunta']);
    $id_setor = !empty($_POST['id_setor']) ? (int)$_POST['id_setor'] : null;

    if ($texto !== '') {
        $sql = "INSERT INTO perguntas (texto_pergunta, status, id_setor) VALUES (:t, true, :id_setor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', $texto);
        $stmt->bindValue(':id_setor', $id_setor, PDO::PARAM_INT);
        $stmt->execute();
    }

    $redirect = "Location: perguntas.php";
    if ($filtro_setor_id) {
        $redirect .= "?setor={$filtro_setor_id}";
    }
    header($redirect);
    exit;
}


// Editar pergunta
if ($acao === 'editar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $texto = trim($_POST['texto_pergunta']);
    $id_setor = !empty($_POST['id_setor']) ? (int)$_POST['id_setor'] : null;

    if ($texto !== '') {
        $sql = "UPDATE perguntas SET texto_pergunta = :t, id_setor = :id_setor WHERE id_pergunta = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', $texto);
        $stmt->bindValue(':id_setor', $id_setor, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    $redirect = "Location: perguntas.php";
    if ($filtro_setor_id) {
        $redirect .= "?setor={$filtro_setor_id}";
    }
    header($redirect);
    exit;
}


// Ativar / Inativar pergunta
if ($acao === 'toggle' && $id) {
    $sql = "UPDATE perguntas SET status = NOT status WHERE id_pergunta = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $redirect = "Location: perguntas.php";
    if ($filtro_setor_id) {
        $redirect .= "?setor={$filtro_setor_id}";
    }
    header($redirect);
    exit;
}


// Excluir pergunta
if ($acao === 'excluir' && $id) {
    $sql = "DELETE FROM perguntas WHERE id_pergunta = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $redirect = "Location: perguntas.php";
    if ($filtro_setor_id) {
        $redirect .= "?setor={$filtro_setor_id}";
    }
    header($redirect);
    exit;
}

//Seleciona p.id_setor para exibir na tabela
$sql = "SELECT p.id_pergunta, p.texto_pergunta, p.status, p.id_setor, s.nome as setor_nome 
        FROM perguntas p
        LEFT JOIN setores s ON p.id_setor = s.id_setor";

$params = [];
if ($filtro_setor_id) {
    // Adiciona filtro WHERE se houver 'setor' na URL
    $sql .= " WHERE p.id_setor = :filtro_setor_id";
    $params[':filtro_setor_id'] = $filtro_setor_id;
}

$sql .= " ORDER BY p.id_pergunta ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
        
        <div class="card admin-form" style="margin-bottom: 20px;">
            <h3>Filtrar Perguntas</h3>
            <form method="GET" action="perguntas.php">
                <label class="admin-label">Filtrar por Setor</label>
                <select name="setor" class="admin-input" onchange="this.form.submit()">
                    <option value="">-- Todos os Setores --</option>
                    <?php foreach ($setores as $s): ?>
                        <option value="<?= (int)$s['id_setor'] ?>" 
                            <?= ($filtro_setor_id == $s['id_setor']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>


        <div class="card admin-form">
            <h3>Criar nova pergunta</h3>

            <form method="POST" action="perguntas.php?acao=criar<?= $filtro_setor_id ? "&setor={$filtro_setor_id}" : "" ?>">
                <label class="admin-label">Texto da Pergunta</label>
                <input type="text" name="texto_pergunta" class="admin-input" required>
                
                <label class="admin-label">Vincular ao Setor</label>
                <select name="id_setor" class="admin-input">
                    <option value="">-- Não vincular (Global) --</option>
                    <?php foreach ($setores as $s): ?>
                        <option value="<?= (int)$s['id_setor'] ?>" 
                            <?= ($filtro_setor_id == $s['id_setor']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                <button type="submit" class="btn">Salvar</button>
            </form>
        </div>


        <div class="card">
            <h3>Perguntas Cadastradas <?= $filtro_setor_id ? " (Setor ID: {$filtro_setor_id})" : "" ?></h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pergunta</th>
                        <th>Setor</th> 
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
                            <?php 
                                $nome_setor = htmlspecialchars($p['setor_nome'] ?? '—');
                                
                                if (!empty($p['id_setor'])) {
                                    $id_setor = (int)$p['id_setor'];
                                    echo "{$nome_setor} ({$id_setor})";
                                } else {
                                    echo $nome_setor;
                                }
                            ?>
                        </td>
                        <td>
                            <?php echo $p['status'] ? "<span class='status ativo'>Ativa</span>"  
                                                     : "<span class='status inativo'>Inativa</span>"; ?>
                        </td>

                        <td class="center">
                            <a class="btn-table edit" 
                            href="perguntas.php?acao=form_editar&id=<?php echo $p['id_pergunta']; ?><?= $filtro_setor_id ? "&setor={$filtro_setor_id}" : "" ?>">
                            Editar
                            </a>

                            <a class="btn-table toggle" 
                            href="perguntas.php?acao=toggle&id=<?php echo $p['id_pergunta']; ?><?= $filtro_setor_id ? "&setor={$filtro_setor_id}" : "" ?>">
                            <?php echo $p['status'] ? "Inativar" : "Ativar"; ?>
                            </a>

                            <a class="btn-table delete"
                            href="perguntas.php?acao=excluir&id=<?php echo $p['id_pergunta']; ?><?= $filtro_setor_id ? "&setor={$filtro_setor_id}" : "" ?>"
                            onclick="return confirm('Deseja realmente excluir esta pergunta?');">
                            Excluir
                            </a>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <?php 
            $editar = null;
            if ($acao === 'form_editar' && $id): 

                $sql = "SELECT * FROM perguntas WHERE id_pergunta = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $editar = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($editar):
        ?>

        <div class="card admin-form highlight">
            <h3>Editar Pergunta #<?php echo $editar['id_pergunta']; ?></h3>

            <form method="POST" action="perguntas.php?acao=editar&id=<?php echo $editar['id_pergunta']; ?><?= $filtro_setor_id ? "&setor={$filtro_setor_id}" : "" ?>">
                <label class="admin-label">Texto da Pergunta</label>
                <input type="text" name="texto_pergunta" value="<?php echo htmlspecialchars($editar['texto_pergunta']); ?>" class="admin-input" required>
                
                <label class="admin-label">Vincular ao Setor</label>
                <select name="id_setor" class="admin-input">
                    <option value="">-- Não vincular (Global) --</option>
                    <?php foreach ($setores as $s): ?>
                        <option value="<?= (int)$s['id_setor'] ?>" 
                            <?= (isset($editar['id_setor']) && $editar['id_setor'] == $s['id_setor']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn">Salvar Alterações</button>
            </form>
        </div>
        <?php endif; endif; ?>
    </div>
</body>
</html>