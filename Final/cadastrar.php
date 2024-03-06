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
            <li><a href="inicio.php">Início</a></li>
            <li><a href="login.php">Entrar</a></li>
            <li><a href="cadastrar.php">Cadastrar</a></li>
            </ul>
            <p id="idData"><?=$horario?></p>
        </menu>
            <section>
                <fieldset id="idCadastrar">
                    <legend>
                        <a href="login.php"><button id="idEntCad">Entrar</button></a>
                    </legend>
                    <h2>Cadastrar</h2>
                    <form action="?acao=cadastrar" method="post" id="idFormCad">
                        <div class="controleCad">
                            <label for="usuarioCad">Usuário:</label>
                            <input type="text" name="usuarioCad" id="idUserCad" onblur="testar(0)">
                            <small class="atencao">Mensagem de erro</small>
                            <small class="regra">Mensagem de regra</small>
                        </div>
                        <div class="controleCad">
                            <label for="nomeCad">Primeiro nome:</label>
                            <input type="text" name="nomeCad" id="idNomeCad" onblur="testar(1)">
                            <small class="atencao">Mensagem de erro</small>
                            <small class="regra">Mensagem de regra</small>
                        </div>
                        <div class="controleCad">
                            <label for="emailCad">Email:</label>
                            <input type="text" name="emailCad" id="idEmailCad" onblur="testar(2)">
                            <small class="atencao">Mensagem de erro</small>
                            <small class="regra">Mensagem de regra</small>
                        </div>
                        <div class="controleCad">
                            <label for="senhaCad">Senha:</label>
                            <input type="password" name="senhaCad" id="idSenha1Cad" onblur="testar(3)" onkeyup="marcaSenha1()">
                            <small class="atencao">Mensagem de erro</small>
                            <small class="regra">Mensagem de regra</small>
                            <ul>
                                <li class="regra" id="idLargCad">Ter no mínimo 08 e no máximo 12 caracteres;</li>
                                <li class="regra" id="idMaiusCad">Ter letras maiúsculas;</li>
                                <li class="regra" id="idMinusCad">Ter letras minúsculas;</li>
                                <li class="regra" id="idNumCad">Ter números;</li>
                                <li class="regra" id="idEspCad">Ter caracteres especiais.</li>
                            </ul>
                        </div>
                        <div class="controleCad">
                            <label for="senha2">Confirme a senha:</label>
                            <input type="password" name="senha2" id="idSenha2Cad" onblur="testar(4)">
                            <small class="atencao">Mensagem de erro</small>
                            <small class="regra">Mensagem de regra</small>
                        </div>
                        <button type="reset" class="enviar">Limpar</button>
                        <button type="submit" class="enviar" id="idCadastrar" onclick="cadastrar()">Enviar</button>
                        <div id="iduser">
                            <h4 id="idErro">
                                <?php
                                    require_once ("php/usuarios.php");

                                    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'cadastrar') {
                                        $usuario = new Cadastrar(
                                            $_POST['usuarioCad'],
                                            $_POST['nomeCad'],
                                            $_POST['emailCad'],
                                            $_POST['senhaCad']
                                        );
                                        // $usuario->cadastro();
                                        echo $usuario->cadastro();
                                    }
                                    
                                ?>
                            </h4>
                        </div>
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