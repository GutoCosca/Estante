<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');
    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'logout') {
        logout();
    }
    session_start();
    if (isset($_SESSION['id_user'])){
        $logado = sessao($_SESSION['user']);
        $ativo = new Atividade();
        $ativo->tempo();
    }
    else {
        logout();
    }
    
    if (isset($_GET['pagina']) && $_GET['pagina'] != null) {
        $pag = $_GET['pagina'];
    }
    else {
        $pag = 1;
    }

    $menuAcao = "inicio";
    if (isset($_REQUEST['acao'])) {
        if ($_REQUEST['acao'] == 'addLivro') {
        $menuAcao = 'adiciona';
        }
        elseif ($_REQUEST['acao'] == 'Buscar') {
            $menuAcao = 'busca';
        }
    }
    elseif (isset($_REQUEST['cond'])) {
        if ($_REQUEST['cond'] == 'add') {
            $menuAcao = 'adiciona';
        }
        elseif ($_REQUEST['cond'] == 'busc') {
            $menuAcao = 'busca';
        }
    }
?>
<body  onload="submenu()">
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
        <section id="idAdicionar">
            <menu id="idSubmenu">
                <ul>
                    <li><a href="?cond=add" onclick="acao('adiciona')">Adicionar Livros</a></li>
                    <li><a href="?cond=busc" onclick="acao('busca')">Buscar Livros</a></li>
                </ul>
            </menu>
            <h3 class="adic">Adicionar Livros</h3>
            <p class="adic">*campos obrigatórios</p>
            <!-- Adicionar livro -->
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addLivro" method="post" enctype="multipart/form-data" id="idFormAdd" class="adic">
                <div id="idArea00">
                    <label for="idlivro" class="obrig">Título do Livro:</label>
                    <input type="text" name="livro" id="idlivro" required>
                </div>
                <div id="idArea01">
                    <label for="idAutor" class="obrig">Autor:</label>
                    <input type="text" name="autor" id="idAutor" required>
                </div>
                <div id="idArea02">
                    <label for="idEditora">Editora:</label>
                    <input type="text" name="editora" id="idEditora">
                    </div>
                <div id="idArea03">
                    <label for="idCompra">Data da Compra:</label>
                    <input type="date" name="compra" id="idCompra">
                </div>
                <div id="idArea04">
                    <input type="submit" value="Adicionar" class="botao">
                    <input type="reset" value="Limpar" class="botao">
                </div>
            </form>
            <h3 id="idTitBusca" class="busc">Localizar Livro</h3>
            <!-- Busca detalhada -->
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get" id="idFormBusca" class="busc">
                <div id="idArea05">
                    <label for="idLetra">Nome:</label>
                    <input type="text" name="letra" id="idLetra">
                </div>
                <div id="idArea06">
                    <select name="tipo" class="seletor" id="idTipo">
                        <option value="livro">Livro</option>
                        <option value="autor">Autor</option>
                    </select>
                    <select name="ordem" class="seletor" id="idOrdem">
                        <option value="ASC">Crescente</option>
                        <option value="DESC">Decrescente</option>
                    </select>
                </div>
                <div id="idArea07">
                    <label for="idCondic">Situação do Livro:</label>
                    <select name="condic" class="seletor" id="idCondic">
                        <option value="0">Estante</option>
                        <option value="1">Emprestado</option>
                        <option value="2">Extraviado</option>
                    </select>
                </div>
                <div id="idArea08">
                    <input type="submit" name="acao" value="Buscar" class="botao esq">
                    <input type="reset" value="Limpar" class="botao dir">
                </div>
            </form>
        </section>
        <section id="idListar">
            <!-- Listagem dos livros -->
            <?php
                if (isset($_SESSION['user'])) {
                    $arqMorto = 0;
                    $arqEmprestar = 0;
                    $ebook = 0;
                    $limite = ($pag -1) *10;
                    $situa = "Sua Estante de Livros";
                    if (isset($_REQUEST['acao'])) {
                        if ($_REQUEST['acao'] == 'addLivro') {
                            $add = new EditLivros(
                                $_SESSION['id_user'],
                                "livros",
                                $_POST['livro'],
                                $_POST['autor'],
                                $_POST['editora'],
                                "","","",
                                $_POST['compra'],
                                "","","","","",
                                $ebook,
                                $arqEmprestar,
                                $arqMorto
                            );
                            $add->addLivros();
                            $listar = new Registros (
                                $_SESSION['id_user'],
                                "livros",
                                "livro","","",
                                $ebook,
                                $arqEmprestar,
                                $arqMorto,
                                $limite
                            );
                        }
                        elseif ($_REQUEST['acao'] == 'Buscar') {
                            if ($_REQUEST['condic'] == 1) {
                                $arqEmprestar = 1;
                                $arqMorto = 0;
                                $situa = "Seus Livros Emprestados";
                            }
                            elseif ($_REQUEST['condic'] == 2) {
                                $arqEmprestar = 0;
                                $arqMorto = 1;
                                $situa = "Seus Livros Extraviados";
                            }

                            $listar = new Registros(
                                $_SESSION['id_user'],
                                "livros",
                                $_GET['tipo'],
                                $_GET['letra'],
                                $_GET['ordem'],
                                $ebook,
                                $arqEmprestar,
                                $arqMorto,
                                $limite
                            );
                        }
                    }
                    else {
                        $listar = new Registros(
                            $_SESSION['id_user'],
                            "livros",
                            "livro","","",
                            $ebook,
                            $arqEmprestar,
                            $arqMorto,
                            $limite
                        );
                    }
                    $listar->total();
                    $count = mysqli_fetch_array($listar->getTbl());
                    if ($count[0] > 0) {
                        $totPag = ceil($count[0]/10);
                        $listar->lista();
            ?>
            <h3 id="idTitEstante"><?=$situa?></h3>
            <div id="idTabela01">
                <table>
                    <thead>
                        <tr id="idIndice">
                        <th>CAPA</th>
                        <th>LIVRO</th>
                        <th>AUTOR</th>
                        <th>EDITORA</th>
                        <th>COMPRA</th>
                        </tr>
                    </thead>
                    <?php
                            while ($tblLista = mysqli_fetch_array($listar->getTbl())) {
                                $descricao ="Livro: ".$tblLista['livro']." - Autor: ".$tblLista['autor'];
                                if ($tblLista['compra'] != null ) {
                                    $compraBR = mesBR($tblLista['compra'])[0];
                                }
                                else {
                                    $compraBR = "";
                                }
                                if ($tblLista['capa'] != null) {
                                    $capa = '<img src="capas/'.$tblLista['capa'].'">';
                                    
                                }
                                else {
                                    $capa = "";
                                }
                    ?>
                    <tr>
                        <td class="listaCapa" id="idCapa" alt="<?=$descricao?>"><?=$capa?></td>
                        <td class="listaNome" id="idLivro"><a href="editLivro.php?acao=editarLivro&buscaCodigo=<?=$tblLista['id_livros']?>"><?=$tblLista['livro']?></a></td>
                        <td class="listaNome" id="idAutor"><?=$tblLista['autor']?></td>
                        <td class="listaNome" id="idEditora"><?=$tblLista['editora']?></td>
                        <td class="listaData" id="idCompra"><?=$compraBR?></td>
                    </tr>
                <?php
                            }
                ?>
                </table>
                <!-- Barra de Navegação -->
                <?php
                    if ($count[0] > 10) {
                        $totPagi = $totPag;
                        if ($pag > 1) {
                            $ant = $pag - 1;
                        }
                        else {
                            $ant = $pag;
                        }

                        if ($pag < $totPagi) {
                            $prox = $pag + 1;
                        }
                        else {
                            $prox = $pag;
                        }
                        $ini = "pagina=1";
                        
                        if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'Buscar') {
                            $tip = "&tipo=".$_GET['tipo'];
                            $ord = "&ordem=".$_GET['ordem'];
                            $cond = "&condic=".$_GET['condic'];
                            $acao = "&acao=".$_GET['acao'];
                            if ($_GET['letra'] != null){
                                $let = "&letra=".$_GET['letra'];
                            }
                            else {
                                $let = "&letra=";
                            }
                            $resp = $let.$tip.$ord.$cond.$acao;
                            $ini = $ini.$resp;
                            $ant = $ant.$resp;
                            $prox = $prox.$resp;
                            $totPagi = $totPag.$resp;
                        }
                        else {
                            $let = "";
                            $tip = "";
                            $ord = "";
                            $cond = "";
                            $acao = "";
                        }
                        ?>
                        <nav>
                            <ul>
                                <li class="pagi"><a href="?<?=$ini?>" class="paginacao"><<</a></li>
                                <li class="pagi"><a href="?pagina=<?=$ant?>" class="paginacao"><</a></li>
                                <li class="pagi" id="idPaginas">Página <?=$pag?> / <?=$totPag?></li>
                                <li class="pagi"><a href="?pagina=<?=$prox?>" class="paginacao">></a></li>
                                <li class="pagi"><a href="?pagina=<?=$totPagi?>" class="paginacao">>></a></li>
                            </ul>
                        </nav>
                    <?php
                        }
                    }
                    else {
                    ?>
                    <h3 id="idTitEstante">Sua Estante de Livros</br>Está Vazia</h3>
                    <?php
                        }
                    }
            ?>
            
        </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
    <script language="javascript">
        
        var condAcao = "<?php echo $menuAcao?>";
        var add = document.getElementsByClassName('adic');
        var filt = document.getElementsByClassName('busc');
        var menu = document.querySelector('#idSubmenu');
        
        if (condAcao == "adiciona") {
            for (var i = 0; i < add.length; i++) {
                add[i].style.display = 'block';
            }
        }
        else if (condAcao == "busca") {
            for (var i= 0; i < filt.length; i++) {
                filt[i].style.display = 'block';
            }
        }

        function submenu() {
            menu.style.marginTop = '0px';
            menu.style.transition = 'margin-top 1s linear 1s';
        }

        function acao(cond) {
            if (cond == "adiciona") {
                for (var i = 0; i < filt.length; i ++) {
                    filt[i].style.dispay = 'none';
                }
                for (var i = 0; i < add.length; i++) {
                    add[i].style.display= 'block';
                }
            }
            else if (cond == "busca") {
                for (var i = 0; i < add.length; i++){
                    add[i].style.display = 'none';
                }
                for (var i = 0; i < filt.length; i++){
                    filt[i].style.display = 'block';
                }
            }
        }

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
</body>
</html>