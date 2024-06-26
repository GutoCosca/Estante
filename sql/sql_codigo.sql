-- Criação do BD Biblioteca e suas tabelas e atributos
-- MySQL
CREATE DATABASE IF NOT EXISTS biblioteca;

USE biblioteca;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuarios int PRIMARY KEY AUTO_INCREMENT,
    usuario varchar(50) NOT NULL,
    nome varchar(180) NOT NULL,
    senha varchar(250) NOT NULL,
    email varchar(120) NOT NULL,
    dt_nasc date
);

CREATE TABLE IF NOT EXISTS logins (
    id_logins int PRIMARY KEY AUTO_INCREMENT,
    id_usuarios int,
    dataIn date,
    horaIn time,
    dataOut date,
    horaOut time
);

CREATE TABLE IF NOT EXISTS livros (
    id_livros int PRIMARY KEY AUTO_INCREMENT,
    id_usuarios int
    livro varchar(200) NOT NULL,
    autor varchar(50) NOT NULL,
    editora varchar(30),
    edicao varchar(10),
    ano int(4),
    isbn varchar(20),
    sinopse varchar(1000),
    opiniao varchar(1000),
    arqmorto boolean NOT NULL DEFAULT FALSE,
    compra date,
    capa varchar(20),
);

CREATE TABLE IF NOT EXISTS revistas (
    id_revistas int PRIMARY KEY AUTO_INCREMENT,
    id_usuarios int
    revista varchar(200) NOT NULL,
    numero int NOT NULL,
    titulo varchar(200) NOT NULL,
    autor varchar(50) NOT NULL,
    editora varchar(30),
    ano int(4),
    issn varchar(20),
    sinopse varchar(1000),
    opiniao varchar(1000),
    arqmorto boolean NOT NULL DEFAULT FALSE,
    compra date,
    capa varchar(20),
);

CREATE TABLE IF NOT EXISTS emprestimo (
    id_emprest int PRIMARY KEY AUTO_INCREMENT,
    id_usuarios int,
    id_livros int,
    id_revistas int,
    nome varchar(50) NOT NULL,
    dt_emprest date NOT NULL,
    dt_devol date
);

CREATE TABLE IF NOT EXISTS forum_pergunta (
    id_pergunta int PRIMARY KEY AUTO_INCREMENT,
    id_usuarios int,    
    id_livros int,
    id_revistas int
    topico varchar(255) NOT NULL,
    detalhe longtext,
    dt_aberta date NOT NULL,
    hr_aberta time NOT NULL,
    dt_fecha date,
    hr_fecha time 
);

CREATE TABLE IF NOT EXISTS forum_resposta (
    id_resposta int PRIMARY KEY AUTO_INCREMENT,
    id_pergunta int,
    id_usuarios int,    
    respoata longtext,
    dt date NOT NULL,
    hr time NOT NULL,
    fechado boolean NOT NULL DEFAULT FALSE
);

ALTER TABLE logins ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE livros ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE revistas ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE emprestimo ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE emprestimo ADD FOREIGN KEY(id_livros) REFERENCES livros (id_livros);
ALTER TABLE emprestimo ADD FOREIGN KEY(id_revistas) REFERENCES revistas (id_revistas);
ALTER TABLE forum_pergunta ADD FOREIGN KEY(id_usuarios)
REFERENCES usuarios (id_usuarios);
ALTER TABLE forum_pergunta ADD FOREIGN KEY(id_livros)
REFERENCES livros (id_livros);
ALTER TABLE forum_pergunta ADD FOREIGN KEY(id_revistas)
REFERENCES revistas (id_revistas);
ALTER TABLE forum_resposta ADD FOREIGN KEY(id_pergunta)
REFERENCES forum_pergunta (id_pergunta);
ALTER TABLE forum_resposta ADD FOREIGN KEY(id_usuarios)
REFERENCES usuarios (id_usuarios);

CREATE VIEW if NOT EXISTS listauser (id_usuarios, username, nome, email) AS
SELECT usuarios.id_usuarios, usuarios.usuario, usuarios.nome, usuarios.email FROM usuarios;

CREATE VIEW IF NOT EXISTS acessos (id_logins, id_usuarios, usuarios, dataIn, horaIn, dataOut, horaOut) AS
SELECT logins.id_logins, logins.id_usuarios, usuarios.usuario, logins.dataIn, logins.horaIn, logins.dataOut, logins.horaOut FROM logins
INNER JOIN usuarios ON logins.id_usuarios = usuarios.id_usuarios;