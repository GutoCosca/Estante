<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Estante Virtual</title>
</head>
<body>
    <main>
        <header>
            <h1>ESTANTE VIRTUAL</h1>
            <div id="idIdent">
                <p class="ident">Bem vindo usuário</p>
                <p class="ident">22 - janeiro - 2024 / 18:45:00</p>
            </div>
        </header>
        <menu>
            <span class="material-symbols-outlined" id="burguer">Menu</span>
            <ul>
                <li><a href="#" onclick="">Início</a></li>
                <li><a href="#" onclick="">Livros</a></li>
                <li><a href="#" onclick="">Revistas</a></li>
                <li><a href="#" onclick="">Forum</a></li>
                <li><a href="#" onclick="">Sair</a></li>
            </ul>
        </menu>
        <section id="idAdicionar">
            <h3>Adicionar Livros</h3>
            <p>*campos obrigatórios</p>
            <form action="" method="post" enctype="multipart/form-data" id="idFormAdd"> <!-- Adicionar na estante -->
                <label for="livro" class="obrig">Título do Livro:</label>
                <input type="text" name="livro" id="idlivro">
                <label for="autor" class="obrig">Autor:</label>
                <input type="text" name="autor" id="idAutor">
                <label for="editora">Editora:</label>
                <input type="text" name="editora" id="idEditora">
                <label for="compra">Data da Compra:</label>
                <input type="date" name="compra" id="idCompra">
                <input type="submit" value="Adicionar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
            <h3 id="idTitBusca">Buscar</h3>
            <form action="" method="get" id="idFormBusca"> <!-- Busca detalhada -->
                <select name="tipo" id="idTipo">
                    <option value="livro">Livro</option>
                    <option value="autor">Autor</option>
                </select>
                <select name="ordem" id="idOrdem">
                    <option value="ASC">Crescente</option>
                    <option value="DESC">Decrescente</option>
                </select>
                <label for="letra">Nome:</label>
                <input type="text" name="letra" id="idLetra">
                <div id="idArq">
                    <label for="arq">Livros Extraviados:</label>
                    <input type="checkbox" name="arq" id="idArqMorto">
                </div>
                <input type="submit" value="Buscar" class="botao">
                <input type="reset" value="Limpar" class="botao">
            </form>
        </section>
        <section id="idListar">
            <h3 id="idTitEstante">Sua Estante de Livros</h3>
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
                <tr>
                    <td class="listaCapa" id="idCapa"><img src="capas/64efb4eda0d8e.jpg" alt=""></td>
                    <td class="listaNome" id="idLivro">Livro01</td>
                    <td class="listaNome" id="idAutor">Autor01</td>
                    <td class="listaNome" id="idEditora">Editora01</td>
                    <td class="listaData" id="idCompra">21 Fevereiro 2021</td>
                </tr>
                <tr>
                    <td class="lista" id="idCapa">Capa02</td>
                    <td class="lista" id="idLivro">Livro02</td>
                    <td class="lista" id="idAutor">Autor02</td>
                    <td class="lista" id="idEditora">Editora02</td>
                    <td class="lista" id="idCompra">21 Fevereiro 2021</td>
                </tr>
                <tr>
                    <td class="lista" id="idCapa">Capa02</td>
                    <td class="lista" id="idLivro">Livro02</td>
                    <td class="lista" id="idAutor">Autor02</td>
                    <td class="lista" id="idEditora">Editora02</td>
                    <td class="lista" id="idCompra">21 Fevereiro 2021</td>
                </tr>
            </table>  <!-- Exibe a estante -->
        </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
</body>
</html>