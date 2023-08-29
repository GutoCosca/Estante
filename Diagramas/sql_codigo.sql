--Criação do BD Biblioteca e suas tabelas e atributos
-- MySQL
CREATE DATABASE IF NOT EXISTS biblioteca;

USE biblioteca;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuarios int PRIMARY KEY AUTO_INCREMENT,
    usuario varchar(50) NOT NULL
    nome varchar(180) NOT NULL,
    senha varchar(250) NOT NULL,
    email varchar(120) NOT NULL,
    dt_nasc date
);

CREATE TABLE IF NOT EXISTS logins (
    id_logins int PRIMARY KEY AUTO_INCREMENT,
    dia date,
    hora time,
    id_usuarios int
);

CREATE TABLE IF NOT EXISTS livros (
    id_livros int PRIMARY KEY AUTO_INCREMENT,
    livro varchar(200) NOT NULL,
    autor varchar(50) NOT NULL,
    editora varchar(30),
    edicao varchar(10),
    ano int(4),
    isbn varchar(20),
    compra date,
    capa varchar(20),
    id_usuarios int
);

CREATE TABLE IF NOT EXISTS revistas (
    id_revistas int PRIMARY KEY AUTO_INCREMENT,
    revista varchar(200) NOT NULL,
    numero int NOT NULL,
    titulo varchar(200) NOT NULL,
    autor varchar(50) NOT NULL,
    editora varchar(30),
    ano int(4),
    issn varchar(20),
    compra date,
    capa varchar(20),
    id_usuarios int
);

ALTER TABLE logins ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE livros ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios);
ALTER TABLE revistas ADD FOREIGN KEY(id_usuarios) REFERENCES usuarios (id_usuarios)

--POSTRGREE
