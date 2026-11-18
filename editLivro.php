<?php
    // Detalhes e edição de um livro (o visual e a lógica ficam em php/pagina_editar.php)
    $cfg = array(
        'tabela' => 'livros',
        'menu' => 'livros',
        'nome' => 'Livro',
        'genero' => 'o',
        'pagina' => 'editLivro.php',
        'acaoVer' => 'editarLivro',
        'acaoEmprest' => 'emprestLivro',
        'voltar' => 'livros.php',
        'rotuloSituacao' => 'Situação do Livro',
        'situacoes' => array(0 => 'Estante', 1 => 'Emprestado', 2 => 'Extraviado'),
        // coluna => (rótulo, ocupa a linha inteira?)
        'ficha' => array(
            'livro' => array('Livro', true),
            'autor' => array('Autor', true),
            'editora' => array('Editora'),
            'edicao' => array('Edição'),
            'ano' => array('Ano'),
            'isbn' => array('ISBN'),
            'compra' => array('Compra', true)
        ),
        'campos' => array(
            'livro' => array('rotulo' => 'Nome do Livro', 'inteiro' => true),
            'autor' => array('rotulo' => 'Nome do Autor', 'inteiro' => true),
            'editora' => array('rotulo' => 'Editora', 'inteiro' => true),
            'edicao' => array('rotulo' => 'Edição'),
            'ano' => array('rotulo' => 'Ano', 'tipo' => 'number'),
            'isbn' => array('rotulo' => 'ISBN'),
            'compra' => array('rotulo' => 'Data da Compra', 'tipo' => 'date')
        ),
        'tituloItem' => function ($reg) { return $reg['livro']; },
        'alterar' => function ($idUser, $codigo, $post, $capa, $ebook, $arqEmprestar, $arqMorto) {
            $altera = new EditLivros(
                $idUser, "livros",
                $post['livro'] ?? '', $post['autor'] ?? '', $post['editora'] ?? '',
                $post['edicao'] ?? '', $post['ano'] ?? '', $post['isbn'] ?? '',
                $post['compra'] ?? '',
                $capa['tmp_name'], $capa['size'], pathinfo($capa['name'], PATHINFO_EXTENSION),
                $post['sinopse'] ?? '', $post['opiniao'] ?? '',
                $ebook, $arqEmprestar, $arqMorto
            );
            $altera->altLivro($codigo);
        }
    );
    require ('php/pagina_editar.php');
