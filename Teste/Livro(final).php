<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/editar(final).css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Estante Virtual</title>
</head>
<body>
    <header>
        <h1>Estante Virtual</h1>
        <p class="identidade">Guto</p>
    </header>
    <nav>
        <span class="material-symbols-outlined" id="burguer" onclick="clickMenu()">Menu</span>
        <menu aria-disabled="itens">
            <ul class="navegador">
                <li><a href="principal.php" onclick="clickItem()">Início</a></a></li>
                <li><a href="livros.php" onclick="clickItem()">Livros</a></li>
                <li><a href="Revistas" onclick="clickMenu()">Revistas</a></li>
                <li><a href="#" onclick="clickITem()">Forum</a></li>
                <li><a href="logout.php" onclick="clickItem()">Sair</a></li>
            </ul>
        </menu>
    </nav>
    <main class="pagina">
        <section id="dados"><!-- Exibe informacoes do livro  -->
            <h3>Dados do Livro</h3></br>
            <table id="tblEdicao01">
                <tr class="visual">
                    <td class="capa"><img src="" alt="">Capa</td>
                    <td class="capa" colspan="3">Situação01</td>
                </tr>
                <tr class="visual">
                    <th>LIVRO:</th>
                    <td class="nomes" id="idNomes" colspan="3">Livro01</td>
                </tr>
                <tr class="visual">
                    <th>AUTOR:</th>
                    <td class="nomes" colspan="3">Autor01</td>
                </tr>
                <tr class="visual">
                    <th>EDITORA:</th>
                    <td class="nomes" colspan="3">Editora01</td>
                </tr>
                <tr class="visual">
                    <th>EDIÇÃO:</th>
                    <td class="nomes">Edição01</td>
                    <th>ANO:</th>
                    <td class="nomes">Ano01</td>
                </tr>
                <tr class="visual">
                    <th class="nomes">ISBN</th>
                    <td>Isbn01</td>
                    <th>COMPRA:</th>
                    <td class="data">Compra01</td>
                </tr>
                <tr class="visual">
                    <th class="opinaTit" colspan="4">SINOPSE:</th>
                </tr>
                <tr class="visual">
                    <td class="opina" colspan="4">Sinopse01</td>
                </tr>
                <tr class="visual">
                    <th class="opinaTit" colspan="4">OPINIÃO:</th>
                </tr>
                <tr>
                    <td class="opina" colspan="4">Opinião01</td>
                </tr>
            </table>
            <h3>Emprestado</h3>
            <table class="listaEmprest">
                <tr>
                    <th colspan="2"class="titEmprest">NOME:</th>
                </tr>
                <tr>
                    <th class="titEmprest">SAÍDA:</th>
                    <th class="titEmprest">ENTRADA:</th>
                </tr>
                <tr>
                    <td colspan="2" class="dadoEmprest" id="idNome">Nome01</td>
                </tr>
                <tr>
                    <td class="dadoEmprest">18/07/2023</td>
                    <td class="dadoEmprest">25/08/2023</td>
                </tr>
            </table>
            <table id="tblEdicao02"><!-- Exibe informacoes do livro(max width 766px)  -->
                <tr>
                    <td class="capa" rowspan="4"><img src="" alt="">Capa01</td>
                </tr>
                <tr>
                    <th>LIVRO:</th>
                    <td class="nomes" colspan="4">Livro01</td>
                    <th>EDITORA:</th>
                    <td class="nomes" colspan="3">Editora01</td>
                </tr>
                <tr>
                    <th>AUTOR:</th>
                    <td class="nomes" colspan="4"> Autor01</td>
                    <th>EDIÇÃO:</th>
                    <td class="nomes">Edição01</td>
                    <th>ANO:</th>
                    <td class="nomes">Ano01</td>
                </tr>
                <tr>
                    <th>COMPRA:</th>
                    <td class="nomes" colspan="4">Compra01</td>
                    <th>ISBN:</th>
                    <td class="nomes" colspan="3">Isbn01</td>
                </tr>
                <tr>
                    <th class="opinaTit" colspan="5">SINOPSE:</th>
                    <th class="opinaTit" colspan="5">OPINIÃO:</th>
                </tr>
                <tr>
                    <td class="opina" colspan="5">Sinopse01</td>
                    <td class="opina" colspan="5">Opinião01</td>
                </tr>
            </table>
        </section>
        <!-- Edicao do livro -->
        <section class="alterar">
            <h2 class="titulo">Alteração dos Dados do Livro</h2>
            <form action="editarLivro.php?acao=alterar&buscaCodigo=1" method="post" enctype="multipart/form-data" class="formDados">
                <div class="posicao01">
                    <label for="capa" id="lblCapa">CAPA:</label>
                    <input type="file" name="capa" class="info" id="idCapa">
                    <label for="livro" id="lblLivro">LIVRO:</label>
                    <input type="text" name="livro" class="info" id="idLivro" value="livro01">
                    <label for="autor" id="lblAutor">AUTOR:</label>
                    <input type="text" name="autor" class="info" id="idAutor" value="autor01">
                    <label for="editora" id="lblEditora">EDITORA</label>
                    <input type="text" name="editora" class="info" id="idEditora" value="editora01">
                </div>
                <div class="posicao02">
                    <label for="edicao" id="lblEdicao">EDIÇÃO:</label>
                    <input type="text" name="edicao" class="info" id="idEdicao" value="edição01">
                    <label for="isbn" id="lblISBN">ISBN:</label>
                    <input type="text" name="isbn" class="info" id="idIsbn" value="isbn01">
                </div>
                <div class="posicao03">
                    <label for="ano" id="lblAno">ANO:</label>
                    <input type="text" name="ano" class="info" id="idAno" value="ano01">
                    <label for="compra" id="lblCompra">COMPRA:</label>
                    <input type="date" name="compra" class="info" id="idCompar" value="21/01/2024">
                </div>
                <div class="posicao04">
                    <label for="sinopse" id="lblSinopse">SINOPSE:</label>
                    <textarea name="sinopse" class="info" id="idSinopse" rows="10" spellcheck="off">sinopse01</textarea>
                    <label for="opiniao" id="lblOpiniao">OPINIÃO:</label>
                    <textarea name="opiniao" class="info" id="idOpiniao" rows="10" spellcheck="off">opiniao01</textarea>
                </div>
                <div class="posicao05">
                    <label for="arqmorto" id="lblArqmorto">LIVRO EXTRAVIADO?</label>
                    <input type="radio" name="arqmorto" id="arq0" value="0"><p class="resp">Sim</p>
                    <input type="radio" name="arqmorto" id="arq1" value="1" checked="checked"><p class="resp">Não</p>
                </div>
                <div class="posicao06">
                    <input type="submit" value="Alterar" class="info">
                    <input type="reset" value="Limpar" class="info">
                </div>
                <div class="posicao07">
                    <label for="emprest" id="lblEmprest">LIVRO EMPRESTADO?</label>
                    <input type="radio" name="emprest" id="idImprest0" value="0"><p class="resp">Sim</p>
                    <input type="radio" name="emprest" id="emprest1" value="1" checked="checked"><p class="resp">Não</p>
                </div>
            </form>
            <h2 class="titulo" id="idTitEmprest">Emprestado:</h2>
            <form action="" method="post" class="emprestar">
                <div class="posicao08">
                    <label for="nome">NOME:</label>
                    <input type="text" name="nome" id="idNome" value="Nome01" class="info">
                </div>
                <div class="posicao09">
                    <label for="saida">DATA EMPRESTADO:</label>
                    <input type="date" name="saida" id="idSaida" value="25/01/2023" class="info">
                </div>
                <div class="posicao10">
                    <label for="entrada">DATA DEVOLUÇÃO:</label>
                    <input type="date" name="entrada" id="idEntrada" value="26/07/2023" class="info">
                </div>
                <div class="posicao11">
                    <input type="submit" value="Atualizar" class="info">
                    <input type="reset" value="cancelar" class="info">
                </div>
            </form>
    </section>
    </main>
    <footer>
        <p class="design">Desenvolvido por Gustavo Coscarello</p>
    </footer>
</body>
</html>