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
    $user = "";
    
    if (isset($_SESSION['user'])) {
        $user = "Bem vindo ".$_SESSION['id_user']."-".$_SESSION['user'];
        $usuario = "Bem vindo ".$_SESSION['user'];
    }
?>
<body>
    <header>
        <h1>Estante Virtual</h1>
        <p><?=$user?></p>
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
            <h3>Adicionar Livros</h3>
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addLivro" method="post" enctype="multipart/form-data">
                <label for="livro">Nome do Livro:</label>
                <input type="text" name="livro" id="idLivro">
                <label for="autor">Nome do Autor:</label>
                <input type="text" name="autor" id="idAutor">
                <label for="editora">Editora:</label>
                <input type="text" name="editora" id="idEditora">
                <label for="compra">Data de Aquisição:</label>
                <input type="date" name="compra" id="idCompra">
                <input type="submit" value="Adicionar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
            <div class="ordLivros">
                <h3>Ordenar:</h3>
                <form action="<?php $_SERVER['PHP_SELF']?>?busca=ordenar&tipo=livro" method="get">
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
                    if (isset($_REQUEST['tipo']))  {
                        $lista = new Registros($_SESSION['id_user'],"livros", $_GET['tipo'], $_GET['letra'], $_GET['ordem']);
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
                            "", "", "");
                        $add->addLivros();
                        $lista = new Registros($_SESSION['id_user'],"livros", "livro", "", "");
                    }
                    else {
                        $lista = new Registros($_SESSION['id_user'],"livros", "livro", "", "");
                        $lista->lista();
                    }
                    if($lista->getTbl() != null){
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
                            $compraBR = mesBR($compra);
                        }
                        else {
                            $compraBR = "";
                        }
                        
                        $capa = $tblLista["capa"];
                ?>
                    <tr>
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
                }
                else {
                    echo "<h3>Sua estante está vazia<br>Adicione novos livros</h3>";
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