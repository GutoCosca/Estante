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
        $usuario = "Bem vindo ".$_SESSION['id_user']."-".$_SESSION['user'];
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
        </menu>
        <section class="livros">
            <?php

                if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'alterar'){
                    $buscacodigo = $_POST['id_livro'];
                    $altera = new Editar(
                        $_SESSION['id_user'],   
                        "livros",
                        ($_POST['livro']),
                        ($_POST['autor']),
                        ($_POST['editora']),
                        ($_POST['edicao']),
                        $_POST['ano'],
                        $_POST['isbn'],
                        $_POST['compra'],
                        $_FILES['capa']['tmp_name'],
                        $_FILES['capa']['size'],
                        pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION)
                    );

                    $altera->alterar($buscacodigo);
                }
                else if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'editarLivro'){
                    $buscacodigo = $_REQUEST['buscaCodigo'];
                }
                else {
                    $buscacodigo = $codigo;
                }

                $busca = new Registros($_SESSION['id_user'],"livros","","","");
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
                
                if ($compra != null){
                    $compraBR = mesBR($compra);
                }
                else {
                    $compraBR = "";
                }
                
                $capa = $tblEditar["capa"];
            ?>

            <form action="<?php $_SERVER['PHP_SELF'] ?>?acao=alterar&<?=$codigo?>" method="post" enctype="multipart/form-data" id="idFormulario">
                <input type="hidden" name="id_livro" id="" value="<?=$codigo?>">
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
                <label for="compra">Data de Aquisição:</label>
                <input type="date" name="compra" id="idCompra" value="<?=$compra?>">
                <p>Capa do livro:</p>
                <label for="capa">Enviar arquivo</label>
                <input type="file" name="capa" id="idCapa">
                <input type="submit" value="Alterar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
            <?php
                    
            ?>
        </section>
        <section class="tblLivros">
            <h2>Livro <?=$livro?></h2>                
                
                        <table>
                            <tr id="roll">
                                <th>CAPA</th>
                                <th>LIVRO</th>
                                <th>AUTOR</th>
                                <th>EDITORA</th>
                                <th>EDIÇÃO</th>
                                <th>ANO</th>
                                <th>ISBN</th>
                                <th>COMPRADO EM</th>
                            </tr>
                            <tr>
                                <td class="capa"><img src="capas/<?=$capa?>" alt="<?=$capa?>"></td>
                                <td class="nomes"><?=$livro?></td>
                                <td class="nomes"><?=$autor?></a></td>
                                <td class=""><?=$editora?></td>
                                <td class="cAno"><?=$edicao?></td>
                                <td class="cAno"><?=$ano?></td>
                                <td class=""><?=$isbn?></td>
                                <td class="data"><?=$compraBR?></td>
                            </tr>
                    
                        </table>
                
                
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
</body>
</html>