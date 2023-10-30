<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <link rel="stylesheet" href="css/listarscreen.css">
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
        <section class="livros"> <!-- Formulário Livros -->
            <h3>Adicionar Livros</h3></br>
            <p id="idObrig">*campos obrigatórios</p>
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addLivro" method="post" enctype="multipart/form-data">
                <label for="livro" class="obrig">Nome do Livro:</label>
                <input type="text" name="livro" id="idLivro" required>
                <label for="autor" class="obrig">Nome do Autor:</label>
                <input type="text" name="autor" id="idAutor" required>
                <label for="editora" class="obrig">Editora:</label>
                <input type="text" name="editora" id="idEditora" required>
                <label for="compra">Data de Aquisição:</label>
                <input type="date" name="compra" id="idCompra">
                <input type="submit" value="Adicionar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
            <div class="ordLivros">
                <h3>Classificar</h3>
                <form action="<?php $_SERVER['PHP_SELF']?>" method="get">
                    <div class="arq">
                        <input type="checkbox" name="arq" id="" class="arqmorto">
                        <label for="arq" class="arqmorto">Exibir os livros fora da estante</label>
                    </div>
                    <div class="tipoLivros">
                        <label for="tipo">Nome:</label>
                        <select name="tipo" id="idTipo">
                            <option value="livro">Livro</option>
                            <option value="autor">Autor</option>
                        </select>
                        <select name="ordem" id="">
                            <option value="ASC">Crescente</option>
                            <option value="DESC">Decrescente</option>
                        </select>
                    </div>
                    <label for="letra">Nome</label>
                    <input type="text" name="letra" id="">
                    <input type="submit" value="Buscar" class="botao">
                    <input type="reset" value="Limpar" class="botao">
                </form>
            </div>
        </section>
        <section class="tblLivros">
            <h2>Sua Estante de Livros</h2>
            <?php
                if (isset($_SESSION['user']))  {
                    $arq = 0;
                    if (isset($_REQUEST['arq'])) {
                        $arq = 1;
                    }
                    
                    if (isset($_REQUEST['tipo']))  {
                        $lista = new Registros($_SESSION['id_user'],"livros", $_GET['tipo'], $_GET['letra'], $_GET['ordem'], $arq);
                    }
                    else if (isset($_REQUEST['acao']) && $_REQUEST["acao"] == 'addLivro'){
                        $add = new Editar (
                            $_SESSION['id_user'],
                            "livros",
                            $_POST['livro'],
                            $_POST['autor'],
                            $_POST['editora'],
                            "", "", "",
                            $_POST['compra'],
                            "", "", "", "", "", $arq);
                        $add->addLivros();
                        $lista = new Registros($_SESSION['id_user'],"livros", "livro", "", "", $arq);
                    }
                    else {
                        $lista = new Registros($_SESSION['id_user'],"livros", "livro", "", "", $arq);
                    }
                    $lista->lista();
                
                    if(mysqli_num_rows($lista->getTbl()) != 0){
            ?>
            <table>
                <thead>
                    <tr id="roll">
                        <th>CAPA</th>
                        <th>LIVRO</th>
                        <th>AUTOR</th>
                        <th>EDITORA</th>
                        <th>COMPRADO EM</th>
                    </tr>
                </thead> 
                <?php
                    while ($tblLista = mysqli_fetch_array($lista->getTbl())) {
                        $codigo = $tblLista["id_livros"];
                        $livro = $tblLista["livro"];
                        $autor = $tblLista["autor"];
                        $editora = $tblLista["editora"];
                        $compra = $tblLista['compra'];
                        
                        if ($compra != null){
                            $compraBR = mesBR($compra)[0];
                        }
                        else {
                            $compraBR = "";
                        }
                        
                        $capa = $tblLista["capa"];
                ?>
                    <tr class="lista">
                        <td class="capa"><img src="capas/<?=$capa?>" alt="<?=$capa?>"></td>
                        <td class="nomes"><a href="editLivro.php?acao=editarLivro&buscaCodigo=<?=$codigo?>"><?=$livro?></td>
                        <td class="nomes"><?=$autor?></a></td>
                        <td class=""><?=$editora?></td>
                        <td class="data"><?=$compraBR?></td>
                    </tr>
                <?php
                    }
                ?>
            </table>
            <?php
                }
                else {
                    echo "<h2>Sua estante está vazia<br>Adicione novos livros</h2>";
                    }
                }
            ?>
        </section>
    </main>
    <footer>
        <div>
            <p class="design">Desenvolvido por Gustavo Coscarello</p>
        </div>
    </footer>
</body>
</html>