<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/usuarios.php');
    session_start();
    $usuario = "";

    if (isset($_SESSION['user'])) {
        $user = "Bem vindo ".$_SESSION['id_user']."-".$_SESSION['user'];
        $usuario = "Bem vindo ".$_SESSION['user'];
    }
?>
<body>
    <header>
        <h1>Estante Virtual</h1>
        <p><?=$user?></p>
    </header>
    <main>
        <menu>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li><a href="livros.php">Livros</a></li>
                <li><a href="revistas.php">Revistas</a></li>
                <li><a href="#">Forum</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
            <p class="identidade"><?=$usuario?></p>
        </menu>
        <section class="livros">
        </section>
        <section class="tblLivros">
            <h2>Sua Estante de Livros</h2>
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
</body>
</html>