<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conectionstring = "host=localhost 
                    port=5432 
                    dbname=local 
                    user=postgres
                    password=1234";
$conection = pg_connect($conectionstring);

if (!$conection) {
    echo "Erro na conexão com o banco de dados.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    $cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

    $errors = [];

    if ($nome === '' || !preg_match('/^[\p{L} \'\-]{2,100}$/u', $nome)) {
        $errors[] = "Nome inválido (apenas letras, espaços, ' e - , 2-100 chars).";
    }
    if ($sobrenome === '' || !preg_match('/^[\p{L} \'\-]{2,100}$/u', $sobrenome)) {
        $errors[] = "Sobrenome inválido (apenas letras, espaços, ' e - , 2-100 chars).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }
    if (strlen($senha) < 6) {
        $errors[] = "Senha deve ter ao menos 6 caracteres.";
    }
    if ($cidade === '' || mb_strlen($cidade) < 2) {
        $errors[] = "Cidade inválida.";
    }
    if ($estado === '' || mb_strlen($estado) < 2) {
        $errors[] = "Estado inválido.";
    }

    if (!empty($errors)) {
        echo "<h3>Erros de validação:</h3><ul>";
        foreach ($errors as $e) {
            echo "<li>" . htmlspecialchars($e) . "</li>";
        }
        echo "</ul><p><a href='atividadeum.html'>Voltar</a></p>";
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $adadospessoa = [
        $nome,
        $sobrenome,
        $email,
        $senha_hash,
        $cidade,
        $estado
    ];

    $resultinsert = pg_query_params($conection,
        "INSERT INTO tbpessoa (pesnome, pessobrenome, pesemail, pespassword, pescidade, pesestado) 
         VALUES ($1, $2, $3, $4, $5, $6)",
        $adadospessoa
    );

    if ($resultinsert) {
        echo "<p>Dados inseridos com sucesso!</p>";
        echo "<p><a href='atividadeum.html'>Voltar</a> | <a href='listardados.php'>Listar cadastros</a></p>";
    } else {
        echo "<p>Erro na inserção dos dados.</p>";
        echo "<p><a href='atividadeum.html'>Voltar</a></p>";
    }
    exit;
}

echo "<p>Acesse este script via formulário.</p>";
?>