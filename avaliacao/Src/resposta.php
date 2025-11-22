<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once 'conexao.php';
    $pdo = getDbConnection();

    // Função para limpar os dados recebidos
    function limpaDados() {
        $comentario = filter_input(INPUT_POST, 'areaFeedback', FILTER_SANITIZE_SPECIAL_CHARS);
        $respostas = $_POST['respostas'] ?? [];
        $setores = $_POST['setores'] ?? []; 
        $dispositivoInfo = filter_input(INPUT_POST, 'id_dispositivo', FILTER_VALIDATE_INT);

        return [
            'comentario_limpo' => $comentario,
            'respostas_limpas' => $respostas,
            'setores_limpos' => $setores,
            'dispositivo_limpo' => $dispositivoInfo
        ];
    }

    // Função para salvar as avaliações no banco
    function salvarAvaliacao($comentario, $respostas, $setores, $pdo) { 
        try {
            $comando = "INSERT INTO avaliacoes (
                id_pergunta,
                resposta,
                feedback_textual,
                id_setor 
            ) VALUES (
                :id_pergunta,
                :resposta,
                :feedback_textual,
                :id_setor 
            )";

            $stmt = $pdo->prepare($comando);

            foreach ($respostas as $id_pergunta => $resposta) {
                $id_setor = $setores[$id_pergunta] ?? null;

                $setor_bind = !empty($id_setor) ? (int)$id_setor : null;

                $stmt->execute([
                    ':id_pergunta' => (int)$id_pergunta,
                    ':resposta' => (int)$resposta,
                    ':feedback_textual' => $comentario,
                    ':id_setor' => $setor_bind 
                ]);
            }

        } catch (PDOException $e) {
            // Em ambiente de produção, não exiba o erro, apenas registre.
            die("Erro ao salvar a avaliação no banco de dados: " . $e->getMessage()); 
        }
    }

    // Função para redirecionar após o envio
    function redirecionar() {
        header("Location: /avaliacao/public/obrigado.html");
        exit;
    }   

    // Executa o fluxo
    $dadoslimpos = limpaDados();
    salvarAvaliacao(
        $dadoslimpos['comentario_limpo'],
        $dadoslimpos['respostas_limpas'],
        $dadoslimpos['setores_limpos'], 
        $pdo
    );

    redirecionar();
}
?>