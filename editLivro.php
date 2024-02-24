<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="teste/editar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');

    if (isset ($_REQUEST['acao']) == null) {
        $site = siteErro();
    }

    session_start();
    $usuario = "";

    if (isset($_SESSION['user'])) { // Verifica se o usuario está logado
        $usuario = "Bem vindo ".$_SESSION['user'];
        $ativo = new Atividade(); // Verifica o tempo de inatividade
        $ativo->tempo();
    }
    $ativo = new Atividade();
    $ativo ->tempo();
?>
<body>
    <header>
    <h1>Estante Virtual</h1>
    <p class="identidade"><?=$usuario?></p>
    </header>
    <nav>
        <span class="material-symbols-outlined" id="burguer" onclick="clickMenu()">menu</span>
        <menu id="itens">
            <ul class="navegador">
                <li><a href="principal.php" onclick="clickItem()">Inicio</a></li>
                <li><a href="livros.php" onclick="clickItem()">Livros</a></li>
                <li><a href="revistas.php" onclick="clickItem()">Revistas</a></li>
                <li><a href="#" onclick="clickItem()">Forum</a></li>
                <li><a href="logout.php" onclick="clickItem()">Sair</a></li>
            </ul>
        </menu>
    </nav>
    <?php
        if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'alterar') {
            $buscacodigo = $_REQUEST['buscaCodigo'];
            $altera = new EditLivros(
                $_SESSION['id_user'],
                "livros",
                $_POST['livro'],
                $_POST['autor'],
                $_POST['editora'],
                $_POST['edicao'],
                $_POST['ano'],
                $_POST['isbn'],
                $_POST['compra'],
                $_FILES['capa']['tmp_name'],
                $_FILES['capa']['size'],
                pathinfo($_FILES['capa']['name'],PATHINFO_EXTENSION),
                $_POST['sinopse'],
                $_POST['opiniao'],
                $_POST['arqmorto'],
            );

            $altera->altlivro($buscacodigo);
        }

        else if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'editarLivro') {
            $buscacodigo = $_REQUEST['buscaCodigo'];
        }

        else {
            
        }
        
        $busca = new Registros($_SESSION['id_user'], "livros", "", "", "", "");
        $busca->buscar($buscacodigo);
        $tblEditar = mysqli_fetch_assoc($busca->getTbl());
        
        if ($tblEditar['arqmorto'] == 1) {
            $situa = "DESAPARECIDO";
            $checked1 = 'checked = "checked"';
            $checked0 = '';
        }

        else {
            $situa = "";
            $checked1 = '';
            $checked0 = 'checked = "checked"';
        }

        if ($tblEditar['compra'] != null) {
            $compraBR = mesBR($tblEditar['compra'])[1];
        }
        else {
            $compraBR = "";
        }
        // var_dump($_POST[]);
    ?>
    <main class="pagina">
        <section id="dados"><!-- Exibe o livro para edição -->
            <h3>Dados do Livro</h3></br>
            <table id="tblEdicao01">
                <tr class="visual">
                    <td class="capa" colspan="2"><img src="capas/<?=$tblEditar['capa']?>" alt="<?=$tblEditar['livro']?>"></td>
                    <td class="capa" colspan="2"><?=$situa?></td>
                </tr>
                <tr class="visual">
                    <th>LIVRO:</th>
                    <td class="nomes" id="idNomes" colspan="3"><?=$tblEditar['livro']?></td>
                </tr>
                <tr class="visual">
                    <th>AUTOR:</th>
                    <td class="nomes" colspan="3"><?=$tblEditar['autor']?></td>
                </tr>
                <tr class="visual">
                    <th>EDITORA:</th>
                    <td class="nomes" colspan="3"><?=$tblEditar['editora']?></td>
                </tr>
                <tr class="visual">
                    <th>EDIÇÃO:</th>
                    <td class="nomes"><?=$tblEditar['edicao']?></td>
                    <th>ANO:</th>
                    <td class="nomes"><?=$tblEditar['ano']?></td>
                </tr>
                <tr class="visual">
                    <th>ISBN:</th>
                    <td class="nomes" colspan="3"><?=$tblEditar['isbn']?></td>
                </tr>
                <tr>
                    <th>COMPRA:</th>
                    <td class="data" colspan="3"><?=$compraBR?></td>
                </tr>
                <tr class="visual">
                    <th class="opinaTit" colspan="4">SINOPSE:</th>
                </tr>
                <tr class="visual">
                    <td class="opina" colspan="4"><?=$tblEditar['sinopse']?></td>
                </tr>
                <tr class="visual">
                    <th class="opinaTit" colspan="4">OPINIÃO:</th>
                </tr>
                <tr class="visual">
                    <td class="opina" colspan="4"><?=$tblEditar['opiniao']?></td>
                </tr>
            </table>
            <table id="tblEdicao02"><!-- Exibe o livro para edição (tela max 766px) -->
                <tr>
                    <td class="capa" rowspan="4"><img src="capas/<?=$tblEditar['capa']?>" alt="<?=$tblEditar['livro']?>"></td>
                    <td class="capa"><?=$situa?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="visual">
                    <th>LIVRO:</th>
                    <td class="nomes" colspan="4"><?=$tblEditar['livro']?></td>
                    <th>EDITORA:</th>
                    <td class="nomes" colspan="3"><?=$tblEditar['editora']?></td>
                </tr>
                <tr>
                    <th>AUTOR:</th>
                    <td class="nomes" colspan="4">Autor01</td>
                    <th>EDIÇÃO:</th>
                    <td class="nomes">Edição01</td>
                    <th>ANO:</th>
                    <td class="nomes"><?=$tblEditar['ano']?></td>
                </tr>
                <tr>
                    <th>ISBN:</th>
                    <td class="nomes" colspan="3"><?=$tblEditar['isbn']?></td>
                    <th>COMPRA:</th>
                    <td class="nomes" colspan="4"><?=$compraBR?></td>
                </tr>
                <tr>
                    <th class="opinaTit" colspan="5">SINOPSE:</th>
                    <th class="opinaTit" colspan="5">OPINIÃO:</th>
                </tr>
                <tr>
                    <td class="opina" colspan="5"><?=$tblEditar['sinopse']?></td>
                    <td class="opina" colspan="5"><?=$tblEditar['opiniao']?></td>
                </tr>
            </table>
        </section>
        <section class="alteracao" id="tabela"> <!-- editar Livros-->
            <h2>Alterar Informações do Livro</h2>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=alterar&buscaCodigo=<?=$tblEditar['id_livros']?>" method="post" enctype="multipart/form-data">
            
                <div class="alte" id="alte01">
                    <div id="idText01">
                        <label for="capa">Capa:</label>
                        <input type="file" name="capa" id="idCapa">
                        <label for="livro">Nome do Livro:</label>
                        <input type="text" name="livro" id="idLivro" value="<?=$tblEditar['livro']?>">
                        <label for="autor">Nome do Autor:</label>
                        <input type="text" name="autor" id="idAutor" value="<?=$tblEditar['autor']?>">
                        <label for="editora">Editora:</label>
                        <input type="text" name="editora" id="idEditora" value="<?=$tblEditar['editora']?>">
                    </div>
                    <div class="divNum" id="divNum01">
                        <label for="edicao" class="formNum" >Edição:</label>
                        <input type="text" name="edicao"  class="formNum" id="idEdicao" value="<?=$tblEditar['edicao']?>">
                        <label for="isbn" class="formNum" >ISBN:</label>
                        <input type="text" name="isbn"  class="formNum" id="idIsbn" value="<?=$tblEditar['isbn']?>">
                    </div>
                    <div class="divNum" id="divNum02">
                        <label for="ano" class="formNum" >Ano:</label>
                        <input type="text" name="ano"  class="formNum" id="idAno" value="<?=$tblEditar['ano']?>">
                        <label for="compra" class="formNum" >Data de Aquisição:</label>
                        <input type="date" name="compra"  class="formNum" id="idCompra" value="<?=$tblEditar['compra']?>">
                    </div>
                </div>
                <div class="alte" id="alte02">
                    <label for="sinopse">Sinopse:</label>
                    <textarea name="sinopse" id=""  rows="10"><?=$tblEditar['sinopse']?></textarea>
                    <label for="opiniao">Opinião:</label>
                    <textarea name="opiniao" id=""  rows="10" spellcheck="off"><?=$tblEditar['opiniao']?></textarea>
                </div>
                <div class="alte" id="alte03"><!-- Complemento das informações do livro -->
                    <label for="arqmorto" class="titulo2">Livro desaparecido?</label>
                    <div class="desapa">
                        <input type="radio" name="arqmorto" id="arq1" value="1" <?=$checked1?> class="checar"><p class="resp">Sim</p>
                        <input type="radio" name="arqmorto" id="arq0" value="0" <?=$checked0?> class="checar"><p class="resp">Não</p>
                    </div>
                    <!-- <label for="emprest">Livro emprestado?</label>
                    <div class="ceder">
                        <input type="radio" name="emprest" id="emp1" value="1" class="checar"><p class="resp">Sim</p>
                        <input type="radio" name="emprest" id="emp0" value="0" class="checar"><p class="resp">Não</p>
                    </div> -->
                </div>
                <div class="alte" id="alte04">
                    </div>
                <div class="alte" id="alte05">
                    <input type="submit" value="Alterar" class="botao">
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p class="design">Desenvolvido por Gustavo Coscarello</p>
    </footer>
    <script>
        function mudouTamanho() {
            if (window.innerWidth >= 766) {
                itens.style.display = 'block';
            }
            else {
                itens.style.display = 'none'
            }
        }

        function clickMenu() {
            if (itens.style.display == 'block') {
                itens.style.display = 'none'
            }
            else {
                itens.style.display = 'block'
            }
        }
    </script>
</body>
</html>