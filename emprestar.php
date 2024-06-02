<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/editar.css">
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
        $pg ="&pagina=".$pag;
    }
    else {
        $pag = 1;
        $pg = "";
    }

    if (isset ($_REQUEST['acao'])) {
        if ($_REQUEST['acao'] == "emprestLivro") {
            $site = "editLivro.php?acao=editarLivro&buscaCodigo=".$_REQUEST['buscaCodigo'];
        }
        elseif ($_REQUEST['acao'] == "emprestRevista") {
            $site = "editRevista.php?acao=editarRevista&buscaCodigo=".$_REQUEST['buscaCodigo'];
        }
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
                <li><a href="<?=$site?>">Voltar</a></li>
                <li><a href="?acao=logout">Sair</a></li>
            </ul>
            <p id="idData"></p>
        </menu>
        <?php
            if (isset($_REQUEST['idNome'])) {
                $idNome = "&idNome=".$_REQUEST['idNome'];
                $idEmprest = $_REQUEST['idNome'];
            }
            else {
                $idNome = "";
                $idEmprest = "";
            }
            if (isset ($_REQUEST['acao'])) {
                if ($_REQUEST['acao'] == "emprestLivro") {
                    $requis = "Livro";
                    $periodico = "livros";
                    $tipo = "do Livro";
                    $dados = new Registros(
                        $_SESSION['id_user'],
                        "livros",
                        "","","","","","",""
                    );
                }
                elseif ($_REQUEST['acao'] == "emprestRevista") {
                    $requis = "Revista";
                    $periodico = "revistas";
                    $tipo = "da Revista";
                    $dados = new Registros(
                        $_SESSION['id_user'],
                        "revistas",
                        "","","","","","",""
                    );
                }
                $teste = "edit".$requis.".php?acao=editar".$requis."&buscaCodigo=".$_REQUEST['buscaCodigo'];
                
                $dados->buscar($_REQUEST['buscaCodigo']);
                $tblDados = mysqli_fetch_array($dados->getTbl());
                if ($periodico === "livros") {
                    $descricao ="Livro: ".$tblDados['livro']." - Autor: ".$tblDados['autor'];
                }
                elseif ($periodico === "revistas") {
                    $descricao ="Revista: ".$tblDados['revista']." - Titulo: ".$tblDados['titulo']." - Numero: ".$tblDados['numero'];
                }
                else {
                    $descricao = "";
                }
                $checkEstante = "";
                $checkEmprestar = "";
                $checkExtarviado = "";
                if ($tblDados['arqempresta'] == 1) {
                    $situa = "EMPRESTADO";
                }
                elseif ($tblDados['arqmorto'] == 1) {
                    $situa = "EXTRAVIADO";
                }
                else {
                    $situa = "";
                }

                if ($tblDados['capa'] != null){
                    $capa = "<img src='capas/".$tblDados['capa']."'>";
                }
                else {
                    $capa = "";
                }

                if ($tblDados['compra'] != null) {
                    $compraBR = mesBR($tblDados['compra'])[1];
                }
                else {
                    $compraBR = "";
                }
                $mensagem = "";
        ?>
        <section id="idDado">
            <h3>Dados <?=$tipo?></h3>
            <?php
                if ($_REQUEST['acao'] == "emprestLivro") {
                    $buscaLiv = $_REQUEST['buscaCodigo'];
                    $buscaRev = "";
                    ?> 
                    <!-- Exibe os dados completo do livro (media screen > 1024px) -->
                    <table id="idTabela01">
                        <tr class="visua0l">
                            <td class="capa" id="idCapa" alt="<?=$descricao?>"><?=$capa?></td>
                            <td class="capa" colspan="3"><?=$situa?></td>
                        </tr>
                        <tr class="visual">
                            <th>LIVRO:</th>
                            <td colspan="3"><?=$tblDados['livro']?></td>
                        </tr>
                        <tr class="visual">
                            <th>AUTOR:</th>
                            <td colspan="3"><?=$tblDados['autor']?></td>
                        </tr>
                        <tr class="visual">
                            <th>EDITORA:</th>
                            <td colspan="3"><?=$tblDados['editora']?></td>
                        </tr>
                        <tr class="visual">
                            <th>EDIÇÃO:</th>
                            <td><?=$tblDados['edicao']?></td>                    
                            <th>ANO:</th>
                            <td><?=$tblDados['ano']?></td>
                        </tr>
                        <tr class="visual">
                            <th>ISBN:</th>
                            <td colspan="3"><?=$tblDados['isbn']?></td>
                        </tr>
                        <tr class="visual">
                            <th>COMPRA:</th>
                            <td colspan="3"><?=$compraBR?></td>
                        </tr>
                        <tr class="visual">
                            <th>SINOPSE:</th>
                        </tr>
                        <tr class="visual">
                            <td class="texto" colspan="4"><?=$tblDados['sinopse']?></td>
                        </tr>
                        <tr class="visual">
                            <th>OPINIÃO:</th>
                        </tr>
                        <tr class="visual">
                            <td class="texto" colspan="4"><?=$tblDados['opiniao']?></td>
                        </tr>
                    </table>
                    <!-- Exibe os dados completo do livro (media screen =< 1024px) -->
                    <div id="idTabela02">
                        <p id="idCapaP"><?=$capa?></p>
                        <p id="idSituaP"><?=$situa?></p>
                        <p class="dadosP01" id="idLivroP01">LIVRO:</p>
                        <p class="dadosP02" id="idLivroP02"><?=$tblDados['livro']?></p>
                        <p class="dadosP01" id="idAutorP01">AUTOR:</p>
                        <p class="dadosP02" id="idAutorP02"><?=$tblDados['autor']?></p>
                        <p class="dadosP01" id="idEditoraP01">EDITORA:</p>
                        <p class="dadosP02" id="idEditoraP02"><?=$tblDados['editora']?></p>
                        <p class="dadosP01" id="idCompraP01">COMPRA:</p>
                        <p class="dadosP02" id="idCompraP02"><?=$compraBR?></p>
                        <p class="dadosP01" id="idIsbnP01">ISBN:</p>
                        <p class="dadosP02" id="idIsbnP02"><?=$tblDados['isbn']?></p>
                        <p class="dadosP01" id="idEdicaoP01">EDIÇÃO:</p>
                        <p class="dadosP02" id="idEdicaoP02"><?=$tblDados['edicao']?></p>
                        <p class="dadosP01" id="idAnoP01">ANO:</p>
                        <p class="dadosP02" id="idAnoP02"><?=$tblDados['ano']?></p>
                        <p class="dadosP01" id="idSinopseP01">SINOPSE:</p>
                        <p class="dadosP02" id="idSinopseP02"><?=$tblDados['sinopse']?></p>
                        <p class="dadosP01" id="idOpiniaoP01">OPINIÃO:</p>
                        <p class="dadosP02" id="idOpiniaoP02"><?=$tblDados['opiniao']?></p>
                    </div>
                    <?php
                }
                elseif ($_REQUEST['acao'] == "emprestRevista") {
                    $buscaLiv = "";
                    $buscaRev = $_REQUEST['buscaCodigo'];
                    ?>
                    <!-- Exibe os dados completo da revista (media screen > 1024px) -->
                    <table id="idTabela01">
                    <tr class="visual">
                            <td class="capa" id="idCapa"><?=$capa?></td>
                            <td class="capa" colspan="3"><?=$situa?></td>
                        </tr>
                        <tr class="visual">
                            <th>REVISTA:</th>
                            <td colspan="3"><?=$tblDados['revista']?></td>
                        </tr>
                        <tr class="visual">
                            <th>NUMERO:</th>
                            <td><?=$tblDados['numero']?></td>
                            <th>ANO:</th>
                            <td><?=$tblDados['ano']?></td>
                        </tr>
                        <tr class="visual">
                            <th>TÍTULO:</th>
                            <td colspan="3"><?=$tblDados['titulo']?></td>
                        </tr>
                        <tr class="visual">
                            <th>AUTOR:</th>
                            <td colspan="3"><?=$tblDados['autor']?></td>
                        </tr>
                        <tr>
                            <th>EDITORA:</th>
                            <td colspan="3"><?=$tblDados['editora']?></td>
                        </tr>
                        <tr class="visual">
                            <th>ISSN:</th>
                            <td colspan="3"><?=$tblDados['issn']?></td>
                        </tr>
                        <tr class="visual">
                            <th>COMPRA:</th>
                            <td colspan="3"><?=$compraBR?></td>
                        </tr>
                        <tr class="visual">
                            <th>SINOPSE:</th>
                        </tr>
                        <tr class="visual">
                            <td class="texto" colspan="4"><?=$tblDados['sinopse']?></td>
                        </tr>
                        <tr class="visual">
                            <th>OPINIÃO:</th>
                        </tr>
                        <tr class="visual">
                            <td class="texto" colspan="4"><?=$tblDados['opiniao']?></td>
                        </tr>
                    </table>
                    <!-- Exibe os dados completo da revista (media screen =< 1024px) -->
                    <div id="idTabela02">
                        <p id="idCapaR"><?=$capa?></p>
                        <p id="idSituaR"><?=$situa?></p>
                        <p class="dadosP01" id="idRevistaR01">REVISTA:</p>
                        <p class="dadosP02" id="idRevistaR02"><?=$tblDados['revista']?></p>
                        <p class="dadosP01" id="idTituloR01">TÍTULO:</p>
                        <p class="dadosP02" id="idTituloR02"><?=$tblDados['titulo']?></p>
                        <p class="dadosP01" id="idAutorR01">AUTOR:</p>
                        <p class="dadosP02" id="idAutorR02"><?=$tblDados['autor']?></p>
                        <p class="dadosP01" id="idEditoraR01">EDITORA:</p>
                        <p class="dadosP02" id="idEditoraR02"><?=$tblDados['editora']?></p>
                        <p class="dadosP01" id="idCompraR01">COMPRA:</p>
                        <p class="dadosP02" id="idCompraR02"><?=$compraBR?></p>
                        <p class="dadosP01" id="idIssnR01">ISSN:</p>
                        <p class="dadosP02" id="idIssnR02"><?=$tblDados['issn']?></p>
                        <p class="dadosP01" id="idNumeroR01">Nº:</p>
                        <p class="dadosP02" id="idNumeroR02"><?=$tblDados['numero']?></p>
                        <p class="dadosP01" id="idAnoR01">ANO:</p>
                        <p class="dadosP02" id="idAnoR02"><?=$tblDados['ano']?></p>
                        <p class="dadosP01" id="idSinopseR01">SINOPSE:</p>
                        <p class="dadosP02" id="idSinopseR02"><?=$tblDados['sinopse']?></p>
                        <p class="dadosP01" id="idOpiniaoR01">OPINIÃO:</p>
                        <p class="dadosP02" id="idOpiniaoR02"><?=$tblDados['opiniao']?></p>
                    </div>;
                    <?php
                }
            ?>
        </section>
        <section id="idAltera">
            <?php
                if (isset($_POST['emprestar'])) {
                    if ($_POST['emprestar'] == "Adicionar") {
                        $AdicEmprest = new Emprestar(
                            $_SESSION['id_user'],"",
                            $buscaLiv,
                            $buscaRev,
                            $_POST['nome'],
                            $_POST['sair'],
                            $_POST['entrar'],
                            ""
                        );
                        $AdicEmprest->addEmprest();
                    }
                    elseif ($_POST['emprestar'] == "Alterar") {
                        $AltEmprest = new Emprestar(
                            $_SESSION['id_user'],
                            $_REQUEST['idNome'],
                            $buscaLiv,
                            $buscaRev,
                            $_POST['nome'],
                            $_POST['sair'],
                            $_POST['entrar'],
                            ""
                        );
                        $AltEmprest->altEmprest();
                        $mensagem = $AltEmprest->altEmprest();
                    }
                }
                $listAberto = new Emprestar(
                    $_SESSION['id_user'],
                    $idEmprest,
                    $buscaLiv,
                    $buscaRev,
                    "","","",""
                );
                $listAberto->listar();
                $tblAberto = mysqli_fetch_array($listAberto->getTbl());
                if (isset($_REQUEST['idNome'])) {
                    $saida = $tblAberto['dt_emprest'];
                    $nome = $tblAberto['nome'];
                    $entra = $tblAberto['dt_devol'];
                }
                else {
                    $saida = "";
                    $nome = "";
                    $entra = "";
                }
            ?>
            <!-- Adicionar e alterar emprestar -->
            <form action="<?=$_SERVER['PHP_SELF']."?acao=".$_REQUEST['acao']."&buscaCodigo=".$_REQUEST['buscaCodigo'].$idNome?>" method="post" id="idFormAltEmprest">
                <div class="itemEmprest" id="idItemEmprest01">
                    <label for="sair">Data da Saída:</label>
                    <input type="date" name="sair" value="<?=$saida?>" id="">
                </div>
                <div class="itemEmprest" id="idItemEmprest02">
                    <label for="entrar">Data da Devolução:</label>
                    <input type="date" name="entrar" value="<?=$entra?>" id="">
                </div>
                <div class="itemEmprest" id="idItemEmprest03">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?=$nome?>" id="">
                </div>
                <div class="itemEmprest" id="idItemEmprest04">
                    <input type="submit" name="emprestar" value="Alterar" class="botao">
                    <input type="submit" name="emprestar" value="Adicionar" class="botao">
                </div>
            </form>
            <?php
                $limite = ($pag - 1) *20;
                $listEmprest = new Emprestar(
                    $_SESSION['id_user'],
                    "",
                    $buscaLiv,
                    $buscaRev,
                    "","","",
                    $limite.",20"
                );
                $listEmprest->total();
                $count = mysqli_fetch_array($listEmprest->getTbl());
                if ($count[0] > 0) {
                    $totPag = ceil($count[0]/20);
                    $listEmprest->listar();
                    $totPagi = $totPag;
                    if ($pag > 1) {
                        $ant = $pag-1;
                    }
                    else {
                        $ant = $pag;
                    }

                    if ($pag < $totPagi) {
                        $prox =$pag+1;
                    }
                    else {
                        $prox = $pag;
                    }
            ?>
            <h1 id="idAviso"><?=$mensagem?></h1>
            <!-- Lista de emprestar -->
            <h3>Lista de Emprestimos:</h3>
            <table id="idTblEmprestar">
                <thead>
                    <tr class="rowEmprest" id="idIndiceEmprest">
                        <th>Nome</th>
                        <th>Saida</th>
                        <th>Entrada</th>
                    </tr>
                </thead>
        <?php
                    while ($tblEmprest = mysqli_fetch_array($listEmprest->getTbl())) {
                        if ($tblEmprest['dt_emprest'] != null){
                            $dt_saida = mesBR($tblEmprest['dt_emprest'])[1];
                        }
                        else {
                            $dt_saida = "";
                        }

                        if ($tblEmprest['dt_devol'] != null){
                            $dt_entra = mesBR($tblEmprest['dt_devol'])[1];
                        }
                        else {
                            $dt_entra = "";
                        }
        ?>
                <tr class="rowEmprest">
                    <td class="colEmprest"><a href="<?=$_SERVER['PHP_SELF']."?acao=".$_REQUEST['acao']."&buscaCodigo=".$_REQUEST['buscaCodigo'].$pg."&idNome=".$tblEmprest['id_emprest']?>"><?=$tblEmprest['nome']?></a></td>
                    <td class="colEmprest"><?=$dt_saida?></td>
                    <td class="colEmprest"><?=$dt_entra?></td>
                </tr>
                <?php
                    }                
                ?>
            </table>
            <!-- Barra de Navegação -->
            <?php
                    if ($count[0] > 20) {
                        $ini = "pagina=1";
                        $urle = "?acao=".$_REQUEST['acao']."&buscaCodigo=".$_REQUEST['buscaCodigo']."&";
                        $ini = $urle.$ini;
                        $ant = $urle."pagina=".$ant;
                        $prox = $urle."pagina=".$prox;
                        $totPagi = $urle."pagina=".$totPag;
            ?>
                        <nav>
                            <ul>
                                <li class="pagi"><a href="<?=$ini?>" class="paginacao"><<</a></li>
                                <li class="pagi"><a href="<?=$ant?>" class="paginacao"><</a></li>
                                <li class="pagi" id="idPaginas">Página <?=$pag?> / <?=$totPag?></li>
                                <li class="pagi"><a href="<?=$prox?>" class="paginacao">></a></li>
                                <li class="pagi"><a href="<?=$totPagi?>" class="paginacao">>></a></li>
                            </ul>
                        </nav>
                    <?php
                    }
                    }
                    else {
                    ?>
                    <h3>Não Existe Histórico de Empréstimos:</h3>
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