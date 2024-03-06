<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicial.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/funcao.php');
    $horario = semanaBR(date('l'))." - ".mesBR(date('Y-m-d'))[1];
?>
<body>
    <main>
        <header>
            <h1>ESTANTE VIRTUAL</h1>
        </header>
        <menu>
            <ul>
                <li><a href="principal.php">Início</a></li>
                <li><a href="login.php">Entrar</a></li>
                <li><a href="cadastrar.php">Cadastrar</a></li>
            </ul>
            <p id="idData"><?=$horario?></p>
        </menu>
            <section>
                <div id="idApresenta">
                    <p class="apresentar">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.</p>
                    <p class="apresentar">Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.</p>
                </div>
            </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
</body>
</html>