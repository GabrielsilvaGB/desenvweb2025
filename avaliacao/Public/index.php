<?php
require_once '../Src/conexao.php'; 
$conn = getDbConnection();

// Pega o id_setor da URL. Se não estiver presente, será null.
$id_setor_url = isset($_GET['setor']) ? (int)$_GET['setor'] : null;

$query = "SELECT id_pergunta, texto_pergunta, id_setor 
          FROM perguntas 
          WHERE status = true";

$params = [];

if ($id_setor_url) {
    // A condição WHERE filtra perguntas que pertencem ao setor especificado
    // OU perguntas que não têm setor vinculado (id_setor IS NULL), que seriam as globais.
    // Assim, o tablet exibe tanto as específicas do setor quanto as gerais.
    $query .= " AND (id_setor = :id_setor OR id_setor IS NULL)";
    $params[':id_setor'] = $id_setor_url;
} else {
    // Se nenhum setor for passado na URL, só exibe perguntas globais
     $query .= " AND id_setor IS NULL";
}

$query .= " ORDER BY id_pergunta";

$stmt = $conn->prepare($query);

// Executa a query com os parâmetros
$stmt->execute($params);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dispositivo_identificador = $id_setor_url ?? 0; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" /> 
    <script>
        const perguntas = <?= json_encode($perguntas) ?>;
        const idSetorAtual = <?= $id_setor_url ?: 'null' ?>;
    </script>
    
    <title>Avaliação</title>
</head>
<body>
    <header>
        <h1>Avalie nossos serviços</h1>
        <p id="pergunta" class="pergunta"></p>
    </header>

    <form id="form-avaliacao" method="POST" action="../Src/resposta.php"> 
        <main class="Container" id="avaliacao-boxes">
            <?php for ($i = 0; $i <= 10; $i++): ?>
                <div class="box box-<?= $i ?>" data-nota="<?= $i ?>"><h2 class="numbox"><?= $i ?></h2></div>
            <?php endfor; ?>
        </main>

        <section id="feedback" style="display: none;">
            <textarea id="comentario" name="areaFeedback" rows="4" cols="50" placeholder="Descreva o motivo da sua nota (opcional)"></textarea>
        </section>

        <input type="hidden" name="id_dispositivo" value="1" />
        <div id="respostas-container"></div>

        <div class="actions">
            <button id="enviar" type="submit" style="display: none;">Deseja enviar sua avaliação?</button>
        </div>
    </form>

    <footer>
        <p>Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.</p>
    </footer>
    <script src="js/script.js" defer></script> 
</body>
</html>