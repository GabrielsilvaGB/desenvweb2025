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
                nota: parseInt(nota)
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
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = `respostas[${resposta.id_pergunta}]`;
                    input.value = resposta.nota;
                    container.appendChild(input);
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