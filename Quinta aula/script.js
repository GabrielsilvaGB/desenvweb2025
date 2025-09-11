const resultado = document.getElementById('resultado');
const buttons = document.querySelectorAll('button');

buttons.forEach(botao => {
    botao.addEventListener('click', () => {
        const valor = botao.innerText;
        if (valor === 'C') {
            resultado.innerText = '';
        }else if (valor === 'X') {
            resultado.innerText +='*' ;
        } else if (valor === '÷') {
            resultado.innerText += '/'
        } else if (valor === '<'){
             resultado.innerText = resultado.innerText.slice(0,-1);
        } else if (valor === '=') {
            resultado.innerText = eval(resultado.innerText);
                if (resultado.innerText < 0) {
                    resultado.style.color = 'red'; 
                } else if (resultado.innerText > 0){
                    resultado.style.color = 'Green';
                } else {
                    resultado.style.color = 'Gray';
                }
        } else {
            resultado.innerText += valor;
            resultado.innerText = res
        }
    });
});
