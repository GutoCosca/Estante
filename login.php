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
    require_once ("php/usuarios.php");
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
            <p id="idData"></p>
        </menu>
            <section>
                <fieldset id="idEntrar">
                    <legend>
                        <a href="cadastrar.php"><button id="idEntCad">Cadastrar</button></a>
                    </legend>
                    <h2>Entrar</h2>
                    <form action="?acao=entrar" method="post" id="idFormLog">
                        <label for="idUserLog">Usuario:</label>
                        <input type="text" name="usuarioLog" id="idUserLog" autocomplete="on">
                        <label for="idSenhaLog">Senha:</label>
                        <input type="password" name="senhaLog" id="idSenhaLog" autocomplete="on">
                        <button type="submit" class="enviar" id="idLogar" onclick="logar()">Enviar</button>
                        <h4 id="idErro">
                            <?php
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
    </body>
<script src="js/cadlog.js"></script>
<script language=javascript type="text/javascript">
    function data(tela) {
        if (tela.matches) {
            semana = new Array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab");
            mes = new Array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");
            hoje = new Date;
        document.querySelector('#idData').innerText = semana[hoje.getDay()] + " - " + hoje.getDate() + " de " + mes[hoje.getMonth()] + " de " + hoje.getFullYear();
        }
        else {
            semana = new Array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
            mes = new Array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
            hoje = new Date;
        document.querySelector('#idData').innerText = semana[hoje.getDay()] + " - " + hoje.getDate() + " de " + mes[hoje.getMonth()] + " de " + hoje.getFullYear();
        }
    }
    var tela = window.matchMedia("(max-width: 1024px)");
    data(tela);
    tela.addEventListener("change", function() {
        data(tela);
    })
</script>
</html>