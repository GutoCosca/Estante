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
    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'logout') {
        logout();
    }
    session_start();
    if (isset($_SESSION['id_user'])) {
        $logado = sessao($_SESSION['user']);
        $ativo = new Atividade();
        $ativo->tempo();
    }
    else {
        logout();
    }
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
                <li><a href="inicio.php">Início</a></li>
                <li><a href="livros.php">Livros</a></li>
                <li><a href="revistas.php">Revistas</a></li>
                <li><a href="forum.php">Forum</a></li>
                <li><a href="?acao=logout">Sair</a></li>
            </ul>
            <p id="idData"></p>
        </menu>
            <section>
                <div id="idApresenta">
                    <p class="apresentar">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br></p>
                </div>
            </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
</body>
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