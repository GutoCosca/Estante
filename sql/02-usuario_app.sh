#!/bin/bash
# Roda só na primeira criação do banco: cria o usuário da aplicação
# com acesso apenas aos dados do banco biblioteca.
mysql -uroot -p"$MYSQL_ROOT_PASSWORD" <<SQL
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
GRANT SELECT, INSERT, UPDATE, DELETE ON biblioteca.* TO '${DB_USER}'@'%';
SQL
