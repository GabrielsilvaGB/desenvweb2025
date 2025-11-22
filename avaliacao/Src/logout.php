<?php
session_start();

// Remove todas as variáveis da sessão
session_unset();

// Destrói a sessão completamente
session_destroy();

// Redireciona para a página de login que está em /public
header("Location: /avaliacao/src/login.php");
exit;
?>
