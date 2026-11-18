FROM php:8.3-apache

# Extensão usada pelo projeto para falar com o MySQL
RUN docker-php-ext-install mysqli

# Apache roda com o mesmo id do usuário dono dos arquivos (1000),
# assim o upload de capas consegue gravar na pasta capas/
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN { \
      echo "date.timezone = America/Sao_Paulo"; \
      echo "upload_max_filesize = 5M"; \
      echo "post_max_size = 8M"; \
      echo "display_errors = On"; \
      echo "error_reporting = E_ALL"; \
    } > /usr/local/etc/php/conf.d/estante.ini

# Não servir arquivos ocultos (.env com as senhas, .git etc.)
RUN printf '<DirectoryMatch "/\\.">\n  Require all denied\n</DirectoryMatch>\n<FilesMatch "^\\.">\n  Require all denied\n</FilesMatch>\n' \
      > /etc/apache2/conf-enabled/ocultos.conf
