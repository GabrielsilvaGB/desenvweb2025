<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once 'conexao.php';
    $pdo = getDbConnection();

    // Função para limpar os dados recebidos
    function limpaDados() {
        $comentario = filter_input(INPUT_POST, 'areaFeedback', FILTER_SANITIZE_SPECIAL_CHARS);
        $respostas = $_POST['respostas'] ?? [];
        // Se você quiser usar id_dispositivo, mantenha esta linha
        $dispositivoInfo = filter_input(INPUT_POST, 'id_dispositivo', FILTER_VALIDATE_INT);

        return [
            'comentario_limpo' => $comentario,
            'respostas_limpas' => $respostas,
            'dispositivo_limpo' => $dispositivoInfo
        ];
    }

    // Função para salvar as avaliações no banco
    function salvarAvaliacao($comentario, $respostas, $pdo) {
        try {
            $comando = "INSERT INTO avaliacoes (
                id_pergunta,
                resposta,
                feedback_textual
            ) VALUES (
                :id_pergunta,
                :resposta,
                :feedback_textual
            )";

            $stmt = $pdo->prepare($comando);

            foreach ($respostas as $id_pergunta => $resposta) {
                $stmt->execute([
                    ':id_pergunta' => (int)$id_pergunta,
                    ':resposta' => (int)$resposta,
                    ':feedback_textual' => $comentario
                ]);
            }

        } catch (PDOException $e) {
            die("Erro ao salvar a avaliação no banco de dados: " . $e->getMessage());
        }
    }

    // Função para redirecionar após o envio
    function redirecionar() {
        header("Location: obrigado.html");
        exit;
    }

    // Executa o fluxo
    $dadoslimpos = limpaDados();
    salvarAvaliacao(
        $dadoslimpos['comentario_limpo'],
        $dadoslimpos['respostas_limpas'],
        $pdo
    );

    redirecionar();
}
?>