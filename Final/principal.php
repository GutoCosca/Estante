<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicial.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');
    session_start();
    $logado = sessao($_SESSION['user']);
    $ativo = new Atividade();
    $ativo->tempo();
?>
<body>
    <main>
        <header>
            <h1>ESTANTE VIRTUAL</h1>
            <div id="idIdent">
                <p class="ident"><?=$logado?></p>
            </div>
        </header>
        <menu>
            <ul>
                <li><a href="principal.php">Início</a></li>
                <li><a href="livros.php">Livros</a></li>
                <li><a href="revistas.php">Revistas</a></li>
                <li><a href="#">Forum</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
            <p id="idData">22 / janeiro / 2024 - 18:45:00</p>
        </menu>
            <section>
                <p class="apresentar">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.</p>
            </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
</body>
</html>