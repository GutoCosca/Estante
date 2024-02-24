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
    $logado = sessao($_SESSION['user']);
    $ativo = new Atividade($_SESSION['user']);
    $ativo->tempo();
    $horario = semanaBR(date('l'))." - ".mesBR(date('Y-m-d'))[1];
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
                <li><a href="?acao=logout">Sair</a></li>
            </ul>
            <p id="idData"><?=$horario?></p>
        </menu>
        <?php
            if ($_REQUEST['acao'] == "emprestLivro") {
                $site = "editlivro.php?acao=editarLivro&buscaCodigo=".$_REQUEST['buscaCodigo'];
                $dados = new Registros(
                    $_SESSION['id_user'],
                    "livros",
                    "","","","","","",""
                );
                $dados->buscar($_REQUEST['buscaCodigo']);
                $tblDados = mysqli_fetch_array($dados->getTbl());
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
                // ?sair=2024-02-08&entrar=&nome=Ricardo&emprestar=Alterar metodo post
                if (isset($_POST['emprestar'])) {
                    if ($_POST['emprestar'] == "Adicionar") {
                        $AdicEmprest = new Emprestar(
                            $_SESSION['id_user'],"",
                            $_REQUEST['buscaCodigo'],
                            "",
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
                            $_REQUEST['buscaCodigo'],
                            "",
                            $_POST['nome'],
                            $_POST['sair'],
                            $_POST['entrar'],
                            ""
                        );
                        $AltEmprest->altEmprest();
                    }
                }

                if (isset($_REQUEST['idNome'])) {
                    $idEmprest = $_REQUEST['idNome'];
                }
                else {
                    $idEmprest = "";
                } 
                $listAberto = new Emprestar(
                    $_SESSION['id_user'],
                    $idEmprest,
                    $_REQUEST['buscaCodigo'],
                    "","","","",
                    1
                );
                $listAberto->listar();
                $tblAberto = mysqli_fetch_array($listAberto->getTbl());
                if (isset($_REQUEST['idNome'])) {
                    $saida = $tblAberto['dt_emprest'];
                    $nome = $tblAberto['nome'];
                    $entra = $tblAberto['dt_devol'];
                }
                else {
                    if ($tblAberto['dt_devol'] != null) {
                        $saida = "";
                        $nome = "";
                        $entra = "";
                    }
                    else {
                        $saida = $tblAberto['dt_emprest'];
                        $nome = $tblAberto['nome'];
                        $entra = $tblAberto['dt_devol'];
                    }
                }
                $listEmprest = new Emprestar(
                    $_SESSION['id_user'],
                    "",
                    $_REQUEST['buscaCodigo'],
                    "","","","",
                    "0,10"
                );
                $listEmprest->listar();
        ?>
        <section id="idDado">
            <h3>Dados do Livro</h3>
            <!-- Exibe os dados completo do livro (media screen > 1024px) -->
            <table id="idTabela01">
                <tr class="visual">
                    <td class="capa" id="idCapa"><?=$capa?></td>
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
        </section>
        <section id="idAltera">
            <form action="<?=$_SERVER['PHP_SELF']."?acao=".$_REQUEST['acao']."&buscaCodigo=".$_REQUEST['buscaCodigo']?>" method="post" id="idFormAltEmprest">
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
                    <input type="reset" value="Limpar" class="botao">
                    <input type="submit" name="emprestar" value="Alterar" class="botao">
                    <input type="submit" name="emprestar" value="Adicionar" class="botao">
                </div>
            </form>
            <button><a href="<?=$site?>">voltar</a></button>
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
                    <td class="colEmprest"><a href="<?=$_SERVER['PHP_SELF']."?acao=".$_REQUEST['acao']."&buscaCodigo=".$_REQUEST['buscaCodigo']."&idNome=".$tblEmprest['id_emprest']?>"><?=$tblEmprest['nome']?></a></td>
                    <td class="colEmprest"><?=$dt_saida?></td>
                    <td class="colEmprest"><?=$dt_entra?></td>
                </tr>
        <?php
                }
            }
        ?>
            </table>
        </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>        
    </main>
</body>
</html>