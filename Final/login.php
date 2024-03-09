<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/acesso.css">
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
            <li><a href="principal.html">Início</a></li>
            <li><a href="login.php">Entrar</a></li>
            <li><a href="cadastrar.php">Cadastrar</a></li>
            </ul>
            <p id="idData"><?=$horario?></p>
        </menu>
            <section>
                <fieldset id="idEntrar">
                    <legend>
                        <a href="cadastrar.php"><button id="idEntCad">Cadastrar</button></a>
                    </legend>
                    <h2>Entrar</h2>
                    <form action="?acao=entrar" method="post" id="idFormLog">
                        <label for="usuarioLog">Usuario:</label>
                        <input type="text" name="usuarioLog" id="idUserLog">
                        <label for="senhaLog">Senha:</label>
                        <input type="password" name="senhaLog" id="idSenhaLog">
                        <button type="submit" class="enviar" id="idLogar" onclick="logar()">Enviar</button>
                        <h4 id="idErro">
                            <?php
                                require_once ("php/usuarios.php");
                                
                                if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'entrar') {
                                    $usuario =new Logar($_POST["usuarioLog"], $_POST['senhaLog']);
                                    $usuario -> validar();
                                    echo $usuario->validar();
                                }
                                ?>
                        </h4>
                    </form>
                </fieldset>
            </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
    <script src="js/cadlog.js"></script>
</body>
</html>