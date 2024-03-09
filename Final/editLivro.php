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
    if (isset($_SESSION['id_user'])){
        $logado = sessao($_SESSION['user']);
        $ativo = new Atividade();
        $ativo->tempo();
        $horario = semanaBR(date('l'))." - ".mesBR(date('Y-m-d'))[1];
    }
    else {
        logout();
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
        <?php
            if (isset($_REQUEST['acao'])){
                if ($_REQUEST['acao'] == 'alterar') {
                    if (isset($_POST['ebook']) && $_POST['ebook'] == 1) {
                        $ebook = $_POST['ebook'];
                        $arqEmprestar = 0;
                        $arqMorto = 0;
                    }
                    else {
                        if ($_POST['situacao'] == 1) {
                            $arqEmprestar = 1;
                            $arqMorto = 0;
                        }
                        elseif ($_POST['situacao'] == 2) {
                            $arqEmprestar = 0;
                            $arqMorto = 1;
                        }
                        else {
                            $arqEmprestar = 0;
                            $arqMorto = 0;
                        }

                        $ebook = 0;
                    }

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
                        pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION),
                        $_POST['sinopse'],
                        $_POST['opiniao'],
                        $ebook,
                        $arqEmprestar,
                        $arqMorto
                    );
                    $altera->altLivro($_REQUEST['buscaCodigo']);
                    
                }
                $dados = new Registros(
                    $_SESSION['id_user'],
                    "livros",
                    "","","","","","",""
                );
                $dados->buscar($_REQUEST['buscaCodigo']);
                $tblDados = mysqli_fetch_array($dados->getTbl());
                $checkEstante = '';
                $checkEmprestar = '';
                $checkExtarviado = '';
                $displayEbook = "";
                $display = 'style="display: none;"';
                if ($tblDados['arqempresta'] == 1) {
                    $situa = "EMPRESTADO";
                    $checkEmprestar = 'checked = "checked"';
                    $display = "";
                }
                elseif ($tblDados['arqmorto'] == 1) {
                    $situa = "EXTRAVIADO";
                    $checkExtarviado = 'checked = "checked"';
                }
                else {
                    $situa = "";
                    $checkEstante = 'checked = "checked"';
                }

                if ($tblDados['ebook'] == 1) {
                    $checkEbook = 'checked';
                    $situa = "eBook";
                    $displayEbook = 'style="display: none;"';
                    $display = 'style="display: none;"';
                }
                else {
                    $checkEbook = "";
                }
                
                if ($tblDados['capa'] != null) {
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
                $emprest = new Emprestar(
                    $_SESSION['id_user'],
                    "",
                    $_REQUEST['buscaCodigo'],
                    "","","","",
                    1
                );
                $emprest->listar();
                $tblEmprest = mysqli_fetch_array($emprest->getTbl());
                    if ($tblEmprest != null) {
                        $nome = $tblEmprest['nome'];
                        $saida = mesBR($tblEmprest['dt_emprest'])[2];
                        if ($tblEmprest['dt_devol'] != null){
                            $entra = mesBR($tblEmprest['dt_devol'])[2];
                        }
                        else {
                            $entra = "";
                        } 
                    }
                    else {
                        $saida = "";
                        $nome = "";
                        $entra = "";
                    }   
            }
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
            <h3>Alterar Dados do Livro</h3>
            <!-- Alteração dos dados do livro -->
            <form action="<?=$_SERVER['PHP_SELF']?>?acao=alterar&buscaCodigo=<?=$tblDados['id_livros']?>" method="post" enctype="multipart/form-data" id="idForm">
                <div id="idItem01">
                    <div id="idArea01">
                        <p>Capa:</p>
                        <label for="capaBotao">Capa</label>
                        <input type="file" name="capa"  id="capaBotao">
                    </div>
                    <div id="idArea02">
                        <input type="checkbox" name="ebook" value="1" <?=$checkEbook?> id="idEbook">
                        <label for="ebook">eBooK</label>
                    </div>
                </div>
                <div id="idItem02">
                    <label for="livro">Nome do Livro:</label>
                    <input type="text" name="livro" id="" value="<?=$tblDados['livro']?>">
                </div>
                <div id="idItem03">
                    <label for="autor">Nome do Autor</label>
                    <input type="text" name="autor" id="" value="<?=$tblDados['autor']?>">
                </div>
                <div id="idItem04">
                    <label for="editora">Nome da Editora:</label>
                    <input type="text" name="editora" id="" value="<?=$tblDados['editora']?>">
                </div>
                <div id="idItem05">
                    <div id="idArea03">
                        <label for="edicao">Edição:</label>
                        <input type="text" name="edicao" class="input01" id="" value="<?=$tblDados['edicao']?>">
                    </div>
                    <div id="idArea04">
                        <label for="ano">Ano:</label>
                        <input type="text" name="ano" class="input01" id="" value="<?=$tblDados['ano']?>">
                    </div>
                </div>
                <div id="idItem06">
                    <div id="idArea05">
                        <label for="isbn">ISBN:</label>
                        <input type="text" name="isbn" class="input01" id="" value="<?=$tblDados['isbn']?>">
                    </div>
                    <div id="idArea06">
                        <label for="compra">Compra:</label>
                        <input type="date" name="compra" class="input01" id="" value="<?=$tblDados['compra']?>">
                    </div>
                </div>
                <div id="idItem07">
                    <label for="sinopse">Sinopse:</label>
                    <textarea name="sinopse" id="" spellcheck="off"><?=$tblDados['sinopse']?></textarea>
                </div>
                <div id="idItem08">
                    <label for="opiniao">Opinião:</label>
                    <textarea name="opiniao" id="" ><?=$tblDados['opiniao']?></textarea>
                </div>
                <div id="idItem09" <?=$displayEbook?>>
                    <label class="areas" for="situacao" id="idSituacao">Situação do Livro:</label>
                    <div class="areas" id="idArea07">
                        <input type="radio" name="situacao" <?=$checkEstante?> id="idOpcao01" value="0">
                        <label for="estante">Estante</label>
                    </div>
                    <div class="areas" id="idArea08">
                        <input type="radio" name="situacao" id="idOpcao02" value="1" <?=$checkEmprestar?>>
                        <label for="emprestar">Emprestado</label>
                    </div>
                    <div class="areas" id="idArea09">
                        <input type="radio" name="situacao" id="idOpcao03" value="2" <?=$checkExtarviado?>>
                        <label for="extraviado">Extraviado</label>
                    </div>
                </div>
                <div id="idItem10">
                    <input type="submit" value="Alterar">
                    <button class="botao"><a id="idVoltar" href="livros.php">Voltar</a></button>
                </div>
            </form>
                <!-- Informação de Emprestimos -->
                <h3 class="emprestar" id="idTitEmprest" <?=$displayEbook?>>Emprestado</h3>
            <div class="emprestar" id="idEmprest" <?=$displayEbook?>>
                <div class="emprestar" id="idItem11">
                    <p class="titulo">Saída:</p>
                    <p class="situa"><?=$saida?></p>
                </div>
                <div class="emprestar" id="idItem12">
                    <p class="titulo">Entrada:</p>
                    <p class="situa"><?=$entra?></p>
                </div>
                <div class="emprestar" id="idItem13">
                    <p class="titulo">Nome:</p>
                    <p class="situa" id="idSituaNome"><?=$nome?></p>
                </div>
                <div class="emprestar" id="idItem14">
                    <button type="submit"><a id="idButEmprest" href="emprestar.php?acao=emprestLivro&buscaCodigo=<?=$_REQUEST['buscaCodigo']?>">Emprestar</a></button>
                </div>
            </div>
            <!-- Criar forum -->
            <h3>Criar Forum</h3>
            <form action="<?=$_SERVER['PHP_SELF']?>?acao=editarLivro&buscaCodigo=<?=$tblDados['id_livros']?>" method="post" id="idFormForum">
                <div class="forum" id="idForum01">
                    <label for="topico">Topico:</label>
                    <input type="text" name="topico" id="idTopico">
                </div>
                <div class="forum" id="idForum02">
                    <label for="detalhe">Detalhe:</label>
                    <textarea name="detalhe" id="idDetalhe" cols="10" rows="10"></textarea>
                </div>
                <div class="forum" id="idForum03">
                    <input class="botao" type="reset" value="Limpar">
                    <input class="botao" type="submit" value="Criar">
                </div>
            </form>
            <?php 
                if (isset($_POST['topico'])){
                    $forum = new Forum(
                        $_SESSION['id_user'],
                        $_REQUEST['buscaCodigo'],
                        $_REQUEST['acao'],
                        $_POST['topico'],
                        $_POST['detalhe']
                    );
                    $forum-> abrir();
                }
            ?>
        </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>    
</body>
</html>