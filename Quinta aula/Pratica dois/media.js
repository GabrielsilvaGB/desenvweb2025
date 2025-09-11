document.addEventListener('DOMContentLoaded', function() {
    const MediaColunas = document.getElementById('MediaColunas');
    const MediaLinhas = document.getElementById('MediaLinhas');
    const tabela = document.getElementById('tabelaNotas');
    
    function calcularMedia(valores) {
        const numeros = valores.map(val => parseFloat(val) || 0);
        const soma = numeros.reduce((acc, val) => acc + val, 0);
        return soma / numeros.length;
    }
    
    function formatarNumero(num) {
        return Number.isInteger(num) ? num.toString() : num.toFixed(2);
    }
    
    MediaColunas.addEventListener('click', function() {
        if (document.querySelector('.media-coluna')) {
            alert('A média das notas já foi calculada!');
            return;
        }
        
        const tbody = tabela.querySelector('tbody');
        const linhas = tbody.querySelectorAll('tr');
        const numColunas = linhas[0].querySelectorAll('td').length;
        
        const novaLinha = document.createElement('tr');
        novaLinha.classList.add('media-coluna');
        
        const tituloCell = document.createElement('td');
        tituloCell.textContent = 'Média de Notas';
        tituloCell.style.fontWeight = 'bold';
        novaLinha.appendChild(tituloCell);
        
        for (let col = 1; col < numColunas; col++) {
            const valores = [];
            
            linhas.forEach(linha => {
                const cell = linha.cells[col];
                if (cell && cell.textContent.trim() !== '') {
                    valores.push(cell.textContent);
                }
            });
            
            const media = calcularMedia(valores);
            const mediaCell = document.createElement('td');
            mediaCell.textContent = formatarNumero(media);
            novaLinha.appendChild(mediaCell);
        }
        
        tbody.appendChild(novaLinha);
        
        this.disabled = true;
        this.style.backgroundColor = '#f82828ff';
        this.style.color = 'black';
        this.textContent = 'Médias das Notas já foram Calculadas';
    });
    
    MediaLinhas.addEventListener('click', function() {
        if (tabela.querySelector('.media-linha')) {
            alert('A média dos alunos já foi calculada!');
            return;
        }
        
        const thead = tabela.querySelector('thead');
        const tbody = tabela.querySelector('tbody');
        const linhas = tbody.querySelectorAll('tr');
        
        const headerRow = thead.querySelector('tr:first-child');
        const mediaHeader = document.createElement('th');
        mediaHeader.textContent = 'Média dos Alunos';
        mediaHeader.rowSpan = 2;
        headerRow.appendChild(mediaHeader);
        
        const secondHeaderRow = thead.querySelector('tr:last-child');
        const emptyHeader = document.createElement('th');
        emptyHeader.style.display = 'none';
        secondHeaderRow.appendChild(emptyHeader);
        
        linhas.forEach(linha => {
            const cells = linha.querySelectorAll('td');
            const valores = [];
            
            for (let i = 1; i < cells.length; i++) {
                if (cells[i].textContent.trim() !== '') {
                    valores.push(cells[i].textContent);
                }
            }
            
            const media = calcularMedia(valores);
            const mediaCell = document.createElement('td');
            mediaCell.textContent = formatarNumero(media);
            mediaCell.classList.add('media-linha');
            linha.appendChild(mediaCell);
        });
        
        const mediaColunaRow = document.querySelector('.media-coluna');
        if (mediaColunaRow) {
            const emptyCell = document.createElement('td');
            emptyCell.textContent = '-';
            mediaColunaRow.appendChild(emptyCell);
        }
        
        this.disabled = true;
        this.style.backgroundColor = '#f82828ff';
        this.style.color = 'black';
        this.textContent = 'Médias dos Alunos já foram Calculadas';
    });
});