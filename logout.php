<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/usuarios.php');
    session_start();
    if (isset ($_SESSION['id_user'])) {
        $logout = new Monitor($_SESSION['id_user']);
        $logout->acesso();
    }
    session_destroy();
?>
<body>
    <header>
        <h1>Estante Virtual</h1>
        <p>Volte Em Breve</p>
    </header>
    <main>
        <menu>
            <ul>
                <li><a href="pagina01.html">Inicio</a></li>
                <li><a href="logar.php">Entrar</a></li>
                <li><a href="cadastrar.php">Cadastrar</a></li>
            </ul>
        </menu>
        <section class="livros">
        </section>
        <section class="tblLivros">
            <h2>Logout</h2>
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
</body>
</html>