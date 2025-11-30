CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE setores (
    id_setor SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE perguntas (
    id_pergunta SERIAL PRIMARY KEY,
    texto_pergunta VARCHAR(255) NOT NULL,
    status BOOLEAN,
    id_setor INTEGER,
    FOREIGN KEY (id_setor) REFERENCES setores(id_setor)
);

CREATE TABLE dispositivos (
    id_dispositivo SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    id_setor INTEGER,
    status BOOLEAN,
    FOREIGN KEY (id_setor) REFERENCES setores(id_setor)
);

CREATE TABLE avaliacoes (
    id_avaliacao SERIAL PRIMARY KEY,
    id_pergunta INTEGER NOT NULL,
    resposta SMALLINT NOT NULL,
    feedback_textual TEXT,
    data_hora TIMESTAMP WITH TIME ZONE,
    id_setor INTEGER,
    FOREIGN KEY (id_pergunta) REFERENCES perguntas(id_pergunta),
    FOREIGN KEY (id_setor) REFERENCES setores(id_setor)
);

INSERT INTO usuarios (usuario, senha) VALUES ('gabriel.silva', '1234');