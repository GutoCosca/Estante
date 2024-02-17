<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="teste/listar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');
    session_start();
    $usuario = "";

    if (isset($_SESSION['user'])) { // Verifica se o usuario está logado
        $usuario = "Bem vindo ".$_SESSION['user'];
        $ativo = new Atividade(); // Verifica o tempo de inatividade
        $ativo->tempo();
    }
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
    <main class="pagina">
        <section id="inserir_dados">
            <h3>Adicionar Livros</h3></br>
            <p>*campos obrigatórios</p>
            <form action="<?php $_SERVER['PHP_SELF']?>?acao=addLivro" method="post" enctype="multipart/form-data" class="adicionar_dados"> <!-- Formulario para inserir livros -->
                <div class="bloco" id="bloco01">
                    <label for="livro" class="obrigatorio">Nome do Livro:</label>
                    <input type="text" name="livro" id="idLivro" required>
                    <label for="autor" class="obrigatorio">Nome do autor:</label>
                    <input type="text" name="autor" id="idAutor" required>
                </div>
                <div class="bloco" id="bloco02">
                    <label for="editora" class="obrigatorio">Editora:</label>
                    <input type="text" name="editora" id="idEditora" required>
                    <label for="compra">Data da Compra:</label>
                    <input type="date" name="compra" id="idCompra">
                </div>
                <div class="bloco" id="bloco03">
                    <input type="submit" value="Adicionar" class="botao">
                    <input type="reset" value="Limpar" class="botao">
                </div>
            </form>
            <h3 id="classificar">Buscar</h3>
            <form action="<?php $_SERVER['PHP_SELF']?>" method="get" class="buscar_dados"> <!-- Formulario para busca de livros -->
                <select name="tipo" class="tipos" id="idTipo">
                    <option value="livro">Livro</option>
                    <option value="autor">Autor</option>
                </select>
                <select name="ordem" class="tipos" id="idOrdem">
                    <option value="ASC">Crescente</option>
                    <option value="DESC">Decrescente</option>
                </select>
                <div class="blocoBusca">
                    <div class="blocoBusca01">
                        <label for="letra">Nome:</label>
                        <input type="text" name="letra" id="idLetra">
                    </div>
                    <div class="blocoBusca02">
                        <input type="checkbox" name="arq" id="idArqMorto" class="arqMorto">
                        <label for="arq" class="arqMorto">Livros desaparecidos</label>
                    </div>
                </div>
                <input type="submit" value="buscar" class="botao">
                <input type="reset" value="limpar" class="botao">
            </form>
        </section>
        <section class="informacao" id="tabela"> <!-- Exibir Livros-->
            <!-- <h2>Sua Estante de Livros</h2> -->
            <?php
                if (isset($_SESSION['user'])) {
                    $arq = 0;
                    $situa = "Sua Estante de Livros";
                    
                    if (isset($_REQUEST['arq'])) {
                        $arq = 1;
                        $situa = "Livros Desaparecidos";
                    }
                    
                    if (isset($_REQUEST['tipo'])) {
                        $lista = new Registros($_SESSION['id_user'], "livros", $_GET['tipo'],$_GET['letra'], $_GET['ordem'], $arq);
                    }
                    
                    else if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'addLivro') {
                        $add = new EditLivros(
                            $_SESSION['id_user'], 
                            "livros", 
                            $_POST['livro'], 
                            $_POST['autor'], 
                            $_POST['editora'], 
                            "", "", "", 
                            $_POST['compra'], 
                            "", "", "", "", "", 
                            $arq);
                        $add -> addLivros();
                        $lista = new Registros($_SESSION['id_user'], "livros", "livro", "", "", $arq);
                    }
                    
                    else {
                        $lista = new Registros($_SESSION['id_user'], "livros", "livro", "", "", $arq);
                    }
                    $lista->lista();
                    
                    if (mysqli_num_rows($lista -> getTbl()) != 0) {
            ?>
            <h2><?=$situa?></h2>
            <table>
                <thead>
                    <tr id="indice">
                        <th>CAPA</th>
                        <th>LIVRO</th>
                        <th>AUTOR</th>
                        <th>EDITORA</th>
                        <th>COMPRA</th>
                    </tr>
                </thead>
                <?php 
                        
                        while ($tblLista = mysqli_fetch_array($lista->getTbl())) {

                            if ($tblLista['compra'] != null) {
                                $compraBR = mesBR($tblLista['compra']) [0];
                            }

                            else {
                                $compraBR = "";
                            }
                    ?>
                    <tr class="dados">
                        <td class="capa"><img src="capas/<?=$tblLista['capa']?>" alt="<?=$tblLista['livro']?>"></td>
                        <td class="nomes" id="idNomes"><a href="editLivro.php?acao=editarLivro&buscaCodigo=<?=$tblLista['id_livros']?>"><?=$tblLista['livro']?></a></td>
                        <td class="nomes"><?=$tblLista['autor']?></td>
                        <td class=""><?=$tblLista['editora']?></td>
                        <td class="data"><?=$compraBR?></td>
                    </tr>
                    <?php
                        }
                    ?>
            </table>
            <?php
                    }
                    
                    else {
                        echo "<h2>está vazia</br>Adicione novos livros</h2>";
                    }
                }
                ?>
        </section>
    </main>
    <footer>
        <p class="design">Desenvolvido por Gustavo Coscarello</p>
    </footer>
    <script>
        function mudouTamanho() {
            if (window.innerWidth >= 766) {
                itens.style.display = 'block'
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
