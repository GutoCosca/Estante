<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');
    session_start();
    $usuario = "";
    
    if (isset($_SESSION['user'])) {
        $usuario = "Bem vindo ".$_SESSION['user'];
    }
?>
<body>
    <header>
        <h1>Estante Virtual</h1>
    </header>
    <main>
        <menu>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li><a href="livros.php">Livros</a></li>
                <li><a href="revistas.php">Revistas</a></li>
                <li><a href="#">Forum</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
            <p class="identidade"><?=$usuario?></p>
        </menu>
        <?php
            if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'alterar'){
                $buscacodigo = $_POST['id_livro'];
                $altera = new Editar(
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
                    pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION),
                    $_POST['sinopse'],
                    $_POST['opiniao'],
                    $_POST['arqmorto'],
                    );
                
                $altera->alterar($buscacodigo);
            }
            else if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'editarLivro'){
                $buscacodigo = $_REQUEST['buscaCodigo'];
            }
            else {
                $buscacodigo = $codigo;
            }

            $busca = new Registros($_SESSION['id_user'],"livros","","","","");
            $busca->buscar($buscacodigo);            
            $tblEditar = mysqli_fetch_assoc($busca->getTbl());

            $codigo = $tblEditar["id_livros"];
            $livro = $tblEditar["livro"];
            $autor = $tblEditar["autor"];
            $editora = $tblEditar["editora"];
            $edicao = $tblEditar["edicao"];
            $ano = $tblEditar["ano"];
            $isbn = $tblEditar["isbn"];
            $compra = $tblEditar["compra"];
            $sinopse = $tblEditar["sinopse"];
            $opiniao = $tblEditar["opiniao"];
            $arqMorto = $tblEditar["arqmorto"];

            if ($arqMorto == 1) {
                $situa = "FORA DA ESTANTE";
                $checked1 = 'checked = "checked"';
                $checked0 = '';
            }
            else {
                $situa = "";
                $checked0 = 'checked = "checked"';
                $checked1 = '';
            }

            if ($compra != null){
                $compraBR = mesBR($compra)[1];
            }
            else {
                $compraBR = "";
            }

            $capa = $tblEditar["capa"];
        ?>
        <section class="livros">
            <h2 id="idEditLivro">Dados do Livro</h2>
            <table class="tbledicao">
                <tr class="visual">
                    <td id="idCapa" colspan="2"><img src="capas/<?=$capa?>" alt="<?=$capa?>"></td>
                    <td colspan="2" class="estante"><?=$situa?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">Livro:</th>
                    <td colspan="3"><?=$livro?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">Autor:</th>
                    <td colspan="3"><?=$autor?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">Editora:</th>
                    <td colspan="3"><?=$editora?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">Edição:</th>
                    <td><?=$edicao?></td>
                    <th class="titulo">Ano:</th>
                    <td><?=$ano?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">ISBN:</th>
                    <td colspan="3"><?=$isbn?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo">Aquisição:</th>
                    <td colspan="3"><?=$compraBR?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo" valign="top">Sinopse:</th>
                    <td colspan="3"><?=$sinopse?></td>
                </tr>
                <tr class="visual">
                    <th class="titulo" valign="top">Opinião:</th>
                    <td colspan="3"><?=$opiniao?></td>
                </tr>
            </table>
        </section>
        <section class="tblLivros">
            <div class="edicao">
                <form action="<?php $_SERVER['PHP_SELF'] ?>?acao=alterar&<?=$codigo?>" method="post" enctype="multipart/form-data" id="idEditLivro">
                    <input type="hidden" name="id_livro" id="" value="<?=$codigo?>">
                    <fieldset>
                        <label for="capa">Capa:</label>
                        <input type="file" name="capa" id="idCapa">
                        <label for="livro">Nome do Livro:</label>
                        <input type="text" name="livro" id="idLivro" value="<?=$livro?>">
                        <label for="autor">Nome do Autor:</label>
                        <input type="text" name="autor" id="idAutor" value="<?=$autor?>">
                        <label for="editora">Editora:</label>
                        <input type="text" name="editora" id="idEditora" value="<?=$editora?>">
                        <label for="edicao">Edição:</label>
                        <input type="text" name="edicao" id="idEdicao" value="<?=$edicao?>">
                        <label for="ano">Ano:</label>
                        <input type="text" name="ano" id="iAno" value="<?=$ano?>">
                        <label for="isbn">ISBN:</label>
                        <input type="text" name="isbn" id="idIsbn" value="<?=$isbn?>">
                    </fieldset>
                    <fieldset>
                        <label for="compra">Data de Aquisição:</label>
                        <input type="date" name="compra" id="idCompra" value="<?=$compra?>">
                        <label for="sinopse">Sinopse:</label>
                        <textarea name="sinopse" id="" cols="100" rows="10"><?=$sinopse?></textarea>
                        <label for="opiniao">Opinião:</label>
                        <textarea name="opiniao" id="" cols="48" rows="10" spellcheck="off"><?=$opiniao?></textarea>
                    </fieldset>
                    <div>
                        <label for="arqmorto">Livro fora da estante?</label>
                        <input type="radio" name="arqmorto" id="1" value="1" <?=$checked1?> >
                        <label for="0">Sim</label>
                        <input type="radio" name="arqmorto" id="0" value="0" <?=$checked0?> >
                        <label for="1">Não</label>
                        <input type="submit" value="Alterar" class="botao">
                        <input type="reset" value="Limpar" class="botao">
                    </div>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
</body>
</html>