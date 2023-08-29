<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/acesso.css">
    
    <title>Estante Virtual</title>
</head>
<body>
    <header>
        <h1>Estante Virtual</h1>
    </header>
    <main>
        <section class="formulario">
            <fieldset class="logar">
                <legend>
                <a href="cadastrar.php"><button id="idCadastro">Cadastrar</button></a>
                </legend>
                <!-- FORMULARIO DE LOGAR -->
                <form action="?acao=entrar" method="post" class="formLog" id="idFormLog">
                    <h2>Entrar</h2>
                    <div class="controleLog">
                        <label for="usuarioLog">Usuário:</label>
                        <input type="text" name="usuarioLog" id="idUserLog">
                    </div>
                    <div class="controleLog">
                        <label for="senhaLog">Senha:</label>
                        <input type="password" name="senhaLog" id="idSenhaLog">
                    </div>
                    <div id="textoErro"><h4 id="erro"></h4></div>
                    <button type="submit" class="enviar" id="idLogar" onclick="logar()">Enviar</button>
                    <div id="iduser">
                            <small id="idUsuario">
                                <?php
                                    require_once ('php/usuarios.php');

                                    if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'entrar') {
                                        $usuario = new Logar (
                                            $_POST['usuarioLog'],
                                            $_POST['senhaLog'],
                                        );
                                        print_r( $usuario->validar());
                                    }
                                    
                                ?>
                        </small>
                    </div>
                </form>                
            </fieldset>
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
    <script src="js/cadlog.js"></script>    
</body>
</html>