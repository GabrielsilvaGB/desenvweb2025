<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /avaliacao/public/admin.php");
    exit;
}

require_once __DIR__ . '/../src/conexao.php';
$pdo = getDbConnection();

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);
    $senha = $_POST['senha'] ?? "";

    if ($usuario && $senha) {

        try {
            $sql = "SELECT id_usuario, usuario, senha 
                    FROM usuarios 
                    WHERE usuario = :u LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":u", $usuario);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dados && $senha === $dados['senha']) {

                $_SESSION['user_id'] = $dados['id_usuario'];
                $_SESSION['username'] = $dados['usuario'];

                header("Location: /avaliacao/public/admin.php");
                exit;

            } else {
                $erro = "Usuário ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao conectar: " . $e->getMessage();
        }

    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo</title>
    <link rel="stylesheet" href="/avaliacao/public/css/style.css">
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">

            <h2 class="login-title">Painel Administrativo</h2>

            <?php if (!empty($erro)) : ?>
                <div class="alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">

                <label class="admin-label">Usuário</label>
                <input type="text" name="usuario" class="admin-input" required>

                <label class="admin-label">Senha</label>
                <input type="password" name="senha" class="admin-input" required>

                <button type="submit" class="btn">Entrar</button>
            </form>
        </div>
    </div>

</body>
</html>
