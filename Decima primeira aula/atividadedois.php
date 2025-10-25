<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$jsonFile = __DIR__ . DIRECTORY_SEPARATOR . 'pessoas.json';
$maxRecords = 10;
$fields = ['nome', 'sobrenome', 'email', 'senha', 'cidade', 'estado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $f) {
        if (!isset($_POST[$f]) || trim($_POST[$f]) === '') {
            $_SESSION['flash_error'] = "Dados incompletos: campo \"{$f}\" faltando.";
            header('Location: atividadedois.html');
            exit;
        }
    }

    $person = [
        'nome' => trim($_POST['nome']),
        'sobrenome' => trim($_POST['sobrenome']),
        'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
        'senha' => password_hash(trim($_POST['senha']), PASSWORD_DEFAULT),
        'cidade' => trim($_POST['cidade']),
        'estado' => trim($_POST['estado']),
        'created_at' => date('c')
    ];

    $people = [];
    if (file_exists($jsonFile)) {
        $json = file_get_contents($jsonFile);
        $data = json_decode($json, true);
        if (is_array($data)) {
            $people = $data;
        } else {
            @rename($jsonFile, $jsonFile . '.corrompido.' . time());
            $people = [];
        }
    }

    $people[] = $person;
    while (count($people) > $maxRecords) {
        array_shift($people);
    }

    $jsonOut = json_encode($people, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($jsonOut === false) {
        $_SESSION['flash_error'] = "Erro ao codificar JSON.";
        header('Location: atividadedois.html');
        exit;
    }

    $tmpFile = $jsonFile . '.tmp';
    if (file_put_contents($tmpFile, $jsonOut, LOCK_EX) !== false && rename($tmpFile, $jsonFile)) {
        $_SESSION['flash_ok'] = "Dados salvos com sucesso. Total de registros: " . count($people);
    } else {
        $_SESSION['flash_error'] = "Erro ao salvar arquivo. Verifique permissões da pasta.";
    }

    header('Location: atividadedois.html');
    exit;
}

exit;
?>