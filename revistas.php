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
        <p><?=$usuario?></p>
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
        <section class="revistas"> <!-- Formulário Revistas -->
            <h3>Adicionar Revistas</h3></br>
            <p id="idObrig">*campos obrigatórios</p>
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addRevista" method="post" enctype="multipart/form-data">
                <label for="revista" class="obrig">Nome do Revista:</label>
                <input type="text" name="revista" id="idRevista" required>
                <label for="numero" class="obrig">Número:</label>
                <input type="text" name="numero" id="idNumero" required>
                <label for="título" class="obrig">Título:</label>
                <input type="text" name="título" id="idTítulo" required>
                <!--<label for="autor">Autor:</label>
                <input type="text" name="autor" id="iAutor">
                <label for="editora">Editora:</label>
                <input type="text" name="editora" id="idEditora">
                <label for="ano">Ano:</label>
                <input type="text" name="ano" id="iAno">
                <label for="issn">ISSN:</label>
                <input type="text" name="issn" id="idIssn">-->
                <label for="compra">Data de Aquisição:</label>
                <input type="date" name="compra" id="idCompra">
                <!--<label for="capa">Capa do Livro:</label>
                <input type="file" name="capa" id="idCapa">-->
                <input type="submit" value="Adicionar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
            <div class="ordRevistas">
                <h3>Classificar</h3>
                <form action="<?php $_SERVER['PHP_SELF']?>" method="get">
                    <div class="arq">
                        <input type="checkbox" name="arq" id="" class="arqmorto">
                        <label for="arq" class="arqmorto">Exibir as revistas fora da estante</label>
                    </div>
                    <div class="tipoRevistas">
                        <label for="tipo">Nome:</label>
                        <select name="tipo" id="idTipo">
                            <option value="revista">Revista</option>
                            <option value="titulo">Título</option>
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
        <section class="tblRevistas">
            <h2>Sua Estante de Revistas</h2>
            <?php
                if (isset($_SESSION['user']))  {
                    $arq = 0;
                    if (isset($_REQUEST['arq'])) {
                        $arq = 1;
                    }

                    if (isset($_REQUEST['tipo'])) {
                        $lista = new Registros($_SESSION['id_user'],"revistas", $_GET['tipo'], $_GET['letra'], $_GET['ordem'], $arq);
                    }
                    else if (isset($_REQUEST['acao']) && $_REQUEST["acao"] == 'addRevista'){
                        $add = new Editar (
                            $_SESSION['id_user'],
                            "revista",
                            $_POST['revista'],
                            $_POST['numero'],
                            $_POST['titulo'],
                            $_POST['autor'],
                            $_POST['editora'],
                            "", "", 
                            $_POST['compra'],
                            "", "", "");
                        $add->adicionar();
                        $lista = new Registros($_SESSION['id_user'],"revistas", "revista", "", "", $arq);
                    }
                    else {
                        $lista = new Registros($_SESSION['id_user'],"revistas", "revista", "", "", $arq);
                    }
                    $lista->lista();
                
                    if($lista->getTbl() != null){
            ?>
            <table>
                <thead>
                    <tr id="roll">
                        <th>CAPA</th>
                        <th>REVISTA</th>
                        <th>NÚMERO</th>
                        <th>TÍTULO</th>
                        <th>COMPRADO EM</th>
                    </tr>
                </thead>
                <?php
                    while ($tblLista = mysqli_fetch_array($lista->getTbl())) {
                        $codigo = $tblLista["id_revistas"];
                        $revista = $tblLista["revista"];
                        $titulo = $tblLista["titulo"];
                        $editora = $tblLista["editora"];
                        $autor = $tblLista['autor'];
                        $numero = $tblLista["numero"];
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
                        <td class="nomes"><a href="editar.php?acao=editarRevista&buscaCodigo=<?=$codigo?>"><?=$revista?></a></td>
                        <td class=""><?=$numero?></td>
                        <td class="nomes"><?=$titulo?></td>
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