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
        $horario = semanaBR(date('l'))." - ".mesBR(date('Y-m-d'))[1];
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
            <p id="idData"><?=$horario?></p>
        </menu>
        <section id="idAdicionar">
            <h3>Adicionar Revistas</h3>
            <p>*campos obrigatórios</p>
            <!-- Adicionar na estante -->
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addRevista" method="post" enctype="multipart/form-data" id="idFormAdd">
                <div id="idArea00">
                    <label for="revista" class="obrig">Nome da Revista:</label>
                    <input type="text" name="revista" id="idlivro" required>
                </div>
                <div id="idArea01">
                    <label for="numero" class="obrig">Numero:</label>
                    <input type="text" name="numero" id="idAutor" required>
                </div>
                <div id="idArea02">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="idEditora">
                    </div>
                <div id="idArea03">
                    <label for="compra">Data da Compra:</label>
                    <input type="date" name="compra" id="idCompra">
                </div>
                <div id="idArea04">
                    <input type="submit" value="Adicionar" class="botao">
                    <input type="reset" value="Limpar" class="botao">
                </div>
            </form>
            <h3 id="idTitBusca">Buscar</h3>
            <!-- Busca detalhada -->
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get" id="idFormBusca">
                <div id="idArea05">
                    <label for="letra">Nome:</label>
                    <input type="text" name="letra" id="idLetra">
                </div>
                <div id="idArea06">
                    <div id="idArq01">
                        <select name="tipo" id="idTipo">
                            <option value="revista">Revista</option>
                            <option value="titulo">Título</option>
                        </select>
                        <select name="ordem" id="idOrdem">
                            <option value="ASC">Crescente</option>
                            <option value="DESC">Decrescente</option>
                        </select>
                    </div>
                    <div id="idArq">
                        <label for="condic" id="idCondic">Situação da Revista:</label>
                        <select name="condic" id="idCondic">
                            <option value="0">Estante</option>
                            <option value="1">Emprestado</option>
                            <option value="2">Extraviado</option>
                        </select>
                    </div>
                </div>
                <div id="idArea07">
                    <input type="submit" name="acao" value="Buscar" class="botao">
                    <input type="reset" value="Limpar" class="botao">
                </div>
            </form>
        </section>
        <section id="idListar">
            <!-- Listagem da estante -->
            <?php
                if (isset($_SESSION['user'])) {
                    $arqMorto = 0;
                    $arqEmprestar = 0;
                    $ebook = 0;
                    $limite = ($pag -1) *10;
                    $situa = "Sua Estante de Revistas";
                    if (isset($_REQUEST['acao'])) {
                        if ($_REQUEST['acao'] == 'addRevista') {
                            $add = new EditRevistas(
                                $_SESSION['id_user'],
                                "revistas",
                                $_POST['revista'],
                                $_POST['numer'],
                                $_POST['titulo'],
                                "","","","",
                                $_POST['compra'],
                                "","","","","",
                                $ebook,
                                $arqEmprestar,
                                $arqMorto
                            );
                            $add->addRevistas();
                            $listar = new Registros (
                                $_SESSION['id_user'],
                                "revistas",
                                "revista","","",
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
                                $situa = "Suas Revistas Emprestados";
                            }
                            elseif ($_REQUEST['condic'] == 2) {
                                $arqEmprestar = 0;
                                $arqMorto = 1;
                                $situa = "Suas Revistas Extraviadas";
                            }

                            $listar = new Registros(
                                $_SESSION['id_user'],
                                "revistas",
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
                            "revistas",
                            "revista","","",
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
                        <th>REVISTA</th>
                        <th>NÚMERO</th>
                        <th>TÍTULO</th>
                        <th>COMPRA</th>
                        </tr>
                    </thead>
                    <?php
                            while ($tblLista = mysqli_fetch_array($listar->getTbl())) {
                                if ($tblLista['compra'] != null ) {
                                    $compraBR = mesBR($tblLista['compra']) [0];
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
                        <td class="listaCapa" id="idCapa"><?=$capa?></td>
                        <td class="listaNome" id="idLivro"><a href="editRevista.php?acao=editarRevista&buscaCodigo=<?=$tblLista['id_revistas']?>"><?=$tblLista['revista']?></a></td>
                        <td class="listaNome" id="idAutor"><?=$tblLista['numero']?></td>
                        <td class="listaNome" id="idEditora"><?=$tblLista['titulo']?></td>
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
                    <h3 id="idTitEstante">Sua Estante de Revistas</br>Está Vazia</h3>
                    <?php
                        }
                    }
            ?>
        </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
</body>
</html>