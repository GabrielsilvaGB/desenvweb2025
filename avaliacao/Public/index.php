<?php
require_once '../Src/conexao.php'; // Caminho ajustado para acessar conexao.php na pasta Src
$conn = getDbConnection();

$query = "SELECT id_pergunta, texto_pergunta FROM perguntas WHERE status = true ORDER BY id_pergunta";
$stmt = $conn->prepare($query);
$stmt->execute();
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" /> <!-- CSS está em Public/css -->
  <script>
    const perguntas = <?= json_encode($perguntas) ?>;
  </script>
  <script src="js/script.js" defer></script> <!-- JS está em Public/js -->
  <title>Avaliação</title>
</head>
<body>
  <header>
    <h1>Avalie nossos serviços</h1>
    <p id="pergunta" class="pergunta"></p>
  </header>

  <form id="form-avaliacao" method="POST" action="../Src/resposta.php"> <!-- Caminho ajustado para resposta.php -->
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
</body>
</html>