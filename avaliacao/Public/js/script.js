let indice = 0;
let respostas = [];

document.addEventListener("DOMContentLoaded", () => {
    mostrarPergunta();

    document.querySelectorAll(".box").forEach(box => {
        box.addEventListener("click", () => {
            const nota = box.getAttribute("data-nota");
            const perguntaAtual = perguntas[indice];

            respostas.push({
                id_pergunta: perguntaAtual.id_pergunta,
                nota: parseInt(nota),
                id_setor: perguntaAtual.id_setor
            });

            indice++;

            if (indice < perguntas.length) {
                mostrarPergunta();
            } else {
                document.getElementById("avaliacao-boxes").style.display = "none";
                document.getElementById("pergunta").style.display = "none";
                document.getElementById("feedback").style.display = "block";
                document.getElementById("enviar").style.display = "inline-block";

                // Monta os inputs ocultos para envio via POST
                const container = document.getElementById("respostas-container");
                respostas.forEach(resposta => {
                    // Input para a resposta (nota)
                    const inputResposta = document.createElement("input");
                    inputResposta.type = "hidden";
                    inputResposta.name = `respostas[${resposta.id_pergunta}]`;
                    inputResposta.value = resposta.nota;
                    container.appendChild(inputResposta);


                    const inputSetor = document.createElement("input");
                    inputSetor.type = "hidden";
                    inputSetor.name = `setores[${resposta.id_pergunta}]`;
                    inputSetor.value = resposta.id_setor || idSetorAtual || '';
                    container.appendChild(inputSetor);
                });
            }
        });
    });
});

function mostrarPergunta() {
    document.getElementById("avaliacao-boxes").style.display = "flex";
    document.getElementById("feedback").style.display = "none";
    document.getElementById("enviar").style.display = "none";
    document.getElementById("pergunta").style.display = "block";
    document.getElementById("pergunta").textContent = perguntas[indice].texto_pergunta;
}

const TEMPO_LIMITE_INATIVIDADE = 180000; //Três minutos
const URL_REDIRECIONAMENTO = './index.php';

let timerInatividade;

function redirecionarParaOutraPagina() {
    window.location.href = URL_REDIRECIONAMENTO;
}

function reiniciarTimerInatividade() {
    clearTimeout(timerInatividade);
    timerInatividade = setTimeout(redirecionarParaOutraPagina, TEMPO_LIMITE_INATIVIDADE);
}

const eventosInteracao = ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'];

eventosInteracao.forEach(evento => {
    document.addEventListener(evento, reiniciarTimerInatividade, false);
});

reiniciarTimerInatividade();