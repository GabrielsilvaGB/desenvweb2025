<?php

//Proteção de sessão
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/public/login.php");
    exit;
}

//Conexão 
require_once __DIR__ . '/../src/conexao.php';
$pdo = getDbConnection();

//Helpers / Sanitização simples
function esc($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

//Ações (criar / editar / toggle / excluir)
$acao = $_GET['acao'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Criar
if ($acao === 'criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $id_setor = !empty($_POST['id_setor']) ? (int)$_POST['id_setor'] : null;
    $status = isset($_POST['status']) ? true : false;

    if ($nome !== '') {
        $sql = "INSERT INTO dispositivos (nome, id_setor, status) VALUES (:nome, :id_setor, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':id_setor' => $id_setor,
            ':status' => $status
        ]);
    }
    header("Location: dispositivos.php");
    exit;
}

// Editar (salvar)
if ($acao === 'editar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $nome = trim($_POST['nome'] ?? '');
    $id_setor = !empty($_POST['id_setor']) ? (int)$_POST['id_setor'] : null;
    $status = isset($_POST['status']) ? true : false;

    if ($nome !== '') {
        $sql = "UPDATE dispositivos SET nome = :nome, id_setor = :id_setor, status = :status WHERE id_dispositivo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':id_setor' => $id_setor,
            ':status' => $status,
            ':id' => $id
        ]);
    }
    header("Location: dispositivos.php");
    exit;
}

//(ativar/inativar)
if ($acao === 'toggle' && $id) {
    $sql = "UPDATE dispositivos SET status = NOT status WHERE id_dispositivo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: dispositivos.php");
    exit;
}

// Excluir
if ($acao === 'excluir' && $id) {

    $sql = "DELETE FROM dispositivos WHERE id_dispositivo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: dispositivos.php");
    exit;
}

// Buscar para edição (quando aciona form_editar)
$editar = null;
if ($acao === 'form_editar' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM dispositivos WHERE id_dispositivo = :id");
    $stmt->execute([':id' => $id]);
    $editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

//Dados para listagem: dispositivos e setores
$dispositivos = $pdo->query("SELECT d.*, s.nome as setor_nome FROM dispositivos d LEFT JOIN setores s ON d.id_setor = s.id_setor ORDER BY d.id_dispositivo")->fetchAll(PDO::FETCH_ASSOC);
$setores = $pdo->query("SELECT * FROM setores ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Dispositivos</title>
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
</head>
<body>

    <div class="admin-container">

            <div class="admin-header">
                <h1>Cadastro de Dispositivos</h1>
                <a href="/avaliacao/public/admin.php" class="btn-secondary">Voltar</a>
            </div>

            <p class="small center">Gerencie os tablets e associe-os a setores (opcional).</p>

            <!-- Formulário de Criação -->
            <div class="form-card">
                <h3><?= $editar ? "Editar dispositivo" : "Adicionar novo dispositivo" ?></h3>

                <?php if ($editar): ?>
                    <form method="POST" action="dispositivos.php?acao=editar&id=<?= (int)$editar['id_dispositivo'] ?>">
                <?php else: ?>
                    <form method="POST" action="dispositivos.php?acao=criar">
                <?php endif; ?>

                    <label>Nome do Dispositivo</label>
                    <input type="text" name="nome" required value="<?= esc($editar['nome'] ?? '') ?>">

                    <label>Setor (opcional)</label>
                    <select name="id_setor">
                        <option value="">-- Selecionar --</option>
                        <?php foreach ($setores as $s): ?>
                            <option value="<?= (int)$s['id_setor'] ?>" <?= (isset($editar['id_setor']) && $editar['id_setor'] == $s['id_setor']) ? 'selected' : '' ?>>
                                <?= esc($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div style="margin-top:8px;">
                        <label><input type="checkbox" name="status" <?= (isset($editar['status']) && $editar['status']) ? 'checked' : '' ?>> Ativo</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn"><?= $editar ? 'Salvar' : 'Adicionar' ?></button>
                        <a href="dispositivos.php" class="btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>

        <!-- Lista de Dispositivos -->
        <div class="card">
            <h3>Dispositivos Cadastrados</h3>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Setor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <tr>
                                <td colspan="5" class="center small">Nenhum dispositivo cadastrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $d): ?>
                                <tr>
                                    <td><?= (int)$d['id_dispositivo'] ?></td>
                                    <td><?= esc($d['nome']) ?></td>
                                    <td><?= esc($d['setor_nome'] ?? '—') ?></td>

                                    <td>
                                        <?php if ($d['status']): ?>
                                            <span class="status ativo">Ativo</span>
                                        <?php else: ?>
                                            <span class="status inativo">Inativo</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="center">
                                        <a class="btn-table edit"
                                        href="dispositivos.php?acao=form_editar&id=<?= (int)$d['id_dispositivo'] ?>">
                                        Editar
                                        </a>

                                        <a class="btn-table toggle"
                                        href="dispositivos.php?acao=toggle&id=<?= (int)$d['id_dispositivo'] ?>">
                                        <?= $d['status'] ? 'Inativar' : 'Ativar' ?>
                                        </a>

                                        <a class="btn-table delete"
                                        href="dispositivos.php?acao=excluir&id=<?= (int)$d['id_dispositivo'] ?>"
                                        onclick="return confirm('Confirma exclusão deste dispositivo?')">
                                        Excluir
                                        </a>
                                    </td>
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
