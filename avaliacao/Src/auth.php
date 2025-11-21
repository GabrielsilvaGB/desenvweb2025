<?php
session_start();

function require_login() {
    if (empty($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
        header('Location: /public/login.php');
        exit;
    }
}

function do_login(PDO $pdo, string $username, string $password): bool {
    $stmt = $pdo->prepare("SELECT id_usuario, username, senha_hash, nome FROM usuarios_admin WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u && password_verify($password, $u['senha_hash'])) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_id'] = $u['id_usuario'];
        $_SESSION['admin_name'] = $u['nome'] ?? $u['username'];
        session_regenerate_id(true);
        return true;
    }

    return false;
}

/** * Encerra a sessão do admin (logout) de forma segura. */
function do_logout() {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}
