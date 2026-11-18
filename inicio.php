<?php
    require_once ('php/funcao.php');
    exigirLogin();

    abrirPagina('Início', 'inicio');
    boasVindas();
?>
        <section class="painel painel-grande">
            <h2>Sua Estante Virtual</h2>
            <p class="subtitulo">Organize seus livros e revistas, controle empréstimos e converse no fórum.</p>
            <div class="botoes" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));margin:22px 0 4px">
                <a class="botao botao-primario" href="livros.php">Meus livros</a>
                <a class="botao botao-secundario" href="revistas.php">Minhas revistas</a>
                <a class="botao botao-secundario" href="forum.php">Fórum</a>
            </div>
        </section>
        <section class="painel">
            <p class="apresentar">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime, fugiat atque pariatur vel rerum veritatis nihil distinctio odit molestiae amet doloribus optio voluptas tempora, animi facere suscipit tempore quo inventore.<br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic aperiam quasi similique nostrum maiores quaerat laudantium corrupti, nisi fugiat mollitia libero tempora ut numquam sapiente iusto voluptate, at placeat molestiae.<br></p>
        </section>
<?php
    fecharPagina();
?>
