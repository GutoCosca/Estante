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
    session_start();
    $logado = sessao($_SESSION['user']);
    // $ativo = new Atividade($_SESSION['user']);
    // $ativo->tempo();
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
                <li><a href="logout.php">Sair</a></li>
            </ul>
            </ul>
            <p id="idData">22 / janeiro / 2024 - 18:45:00</p>
        </menu>
        <section id="idAdicionar">
            <h3>Adicionar Livros</h3>
            <p>*campos obrigatórios</p>
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addLivro" method="post" enctype="multipart/form-data" id="idFormAdd"> <!-- Adicionar na estante -->
                <div id="idArea00">
                    <label for="livro" class="obrig">Título do Livro:</label>
                    <input type="text" name="livro" id="idlivro" required>
                </div>
                <div id="idArea01">
                    <label for="autor" class="obrig">Autor:</label>
                    <input type="text" name="autor" id="idAutor" required>
                </div>
                <div id="idArea02">
                    <label for="editora">Editora:</label>
                    <input type="text" name="editora" id="idEditora">
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
            <form action="<?php $_SERVER['PHP_SELF']?>" method="get" id="idFormBusca"> <!-- Busca detalhada -->
                <div id="idArea05">
                    <label for="letra">Nome:</label>
                    <input type="text" name="letra" id="idLetra">
                </div>
                <div id="idArea06">
                    <select name="tipo" id="idTipo">
                        <option value="livro">Livro</option>
                        <option value="autor">Autor</option>
                    </select>
                    <select name="ordem" id="idOrdem">
                        <option value="ASC">Crescente</option>
                        <option value="DESC">Decrescente</option>
                    </select>
                    <div id="idArq">
                        <label for="condic" id="idCondic">Situação do Livro:</label>
                        <select name="condic" id="idCondic">
                            <option value="0">Estante</option>
                            <option value="1">Extraviado</option>
                            <option value="2">Emprestado</option>
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
            <?php
                if (isset($_SESSION['user'])) {
                    $arqMorto = 0;
                    $arqEmprestar = 0;
                    $limite = 0;
                    $situa = "Sua Estante de Livros";

                    $reg = new Registros(
                        $_SESSION['id_user'],
                        "livros",
                        "livro", "", "",
                        $arqEmprestar,
                        $arqMorto,
                        $limite
                    );
                    $reg->total();
                    $tblReg = mysqli_fetch_array($reg->getTbl());
                    $pag = ceil ($tblReg[0]/10);
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
                                $arqEmprestar,
                                $arqMorto
                            );
                            $add->addLivros();
                            $listar = new Registros (
                                $_SESSION['id_user'],
                                "livros",
                                "livro","","",
                                $arqEmprestar,
                                $arqMorto,
                                $limite
                            );
                        }

                        elseif ($_REQUEST['acao'] == 'Buscar') {
                            if ($_REQUEST['condic'] == 1) {
                                $arqEmprestar = 0;
                                $arqMorto = 1;
                                $situa = "Seua Livros Extraviados";
                            }

                            elseif ($_REQUEST['condic'] == 2) {
                                $arqEmprestar = 1;
                                $arqMorto = 0;
                                $situa = "Seus Livros Emprestados";
                            }

                            $listar = new Registros(
                                $_SESSION['id_user'],
                                "livros",
                                $_GET['tipo'],
                                $_GET['letra'],
                                $_GET['ordem'],
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
                            $arqEmprestar,
                            $arqMorto,
                            $limite
                        );
                    }
                    $listar->lista();
                    if ($tblReg[0] != null) {
                        
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
                        <td class="listaNome" id="idLivro"><a href="editLivro.php?acao=editarLivro&buscaCodigo=<?=$tblLista['id_livros']?>"><?=$tblLista['livro']?></a></td>
                        <td class="listaNome" id="idAutor"><?=$tblLista['autor']?></td>
                        <td class="listaNome" id="idEditora"><?=$tblLista['editora']?></td>
                        <td class="listaData" id="idCompra"><?=$compraBR?></td>
                    </tr>
                    <?php
                            }
                    ?>
                </table>
            </div>
                        <nav style="background-color: aqua;">
                            <ul>
                                <li><<</li>
                                <li><</li>
                                <li>1 - <?=$pag?></li>
                                <li>></li>
                                <li>>></li>
                            </ul>
                        </nav>
            <?php
                    }

                    else {
                        echo '<h3 id="idTitEstante">Sua Estante de Livros</br>Está Vazia</h3>';
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