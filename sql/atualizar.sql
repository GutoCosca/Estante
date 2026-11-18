-- Atualiza um banco "biblioteca" criado com a versão antiga do sql_codigo.sql.
-- Pode ser executado mais de uma vez: cada coluna só é criada se ainda não existir.
-- Como rodar:  mysql -u root biblioteca < sql/atualizar.sql
--   ou no phpMyAdmin: selecione o banco "biblioteca" > aba SQL > cole este arquivo.

USE biblioteca;

DROP PROCEDURE IF EXISTS add_coluna;

DELIMITER //
CREATE PROCEDURE add_coluna(IN tabela VARCHAR(64), IN coluna VARCHAR(64), IN definicao VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tabela AND COLUMN_NAME = coluna
    ) THEN
        SET @comando = CONCAT('ALTER TABLE ', tabela, ' ADD COLUMN ', coluna, ' ', definicao);
        PREPARE stmt FROM @comando;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END //
DELIMITER ;

CALL add_coluna('livros',         'ebook',       'boolean NOT NULL DEFAULT FALSE');
CALL add_coluna('livros',         'arqempresta', 'boolean NOT NULL DEFAULT FALSE');
CALL add_coluna('revistas',       'ebook',       'boolean NOT NULL DEFAULT FALSE');
CALL add_coluna('revistas',       'arqempresta', 'boolean NOT NULL DEFAULT FALSE');
CALL add_coluna('forum_pergunta', 'fechado',     'boolean NOT NULL DEFAULT FALSE');
CALL add_coluna('usuarios',       'admin',       'boolean NOT NULL DEFAULT FALSE');

DROP PROCEDURE add_coluna;

-- Depois, defina quem é administrador (acesso à analise.php):
-- UPDATE usuarios SET admin = TRUE WHERE usuario = 'seu_usuario';
