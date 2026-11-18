<?php
    // Estante de livros (o visual e a lógica ficam em php/pagina_lista.php)
    $cfg = array(
        'tabela' => 'livros',
        'id' => 'id_livros',
        'menu' => 'livros',
        'nome' => 'Livro',
        'plural' => 'Livros',
        'genero' => 'o',
        'acaoAdd' => 'addLivro',
        'campoPrincipal' => 'livro',
        'editar' => 'editLivro.php?acao=editarLivro&buscaCodigo=',
        'rotuloSituacao' => 'Situação do Livro',
        'tipos' => array('livro' => 'Livro', 'autor' => 'Autor'),
        'situacoes' => array(0 => 'Estante', 1 => 'Emprestado', 2 => 'Extraviado'),
        'titulos' => array('Sua Estante de Livros', 'Seus Livros Emprestados', 'Seus Livros Extraviados'),
        'campos' => array(
            'livro'   => array('rotulo' => 'Título do Livro', 'obrig' => true, 'inteiro' => true),
            'autor'   => array('rotulo' => 'Autor', 'obrig' => true, 'inteiro' => true),
            'editora' => array('rotulo' => 'Editora'),
            'compra'  => array('rotulo' => 'Data da Compra', 'tipo' => 'date')
        ),
        'tituloItem' => function ($item) { return $item['livro']; },
        'detalheItem' => function ($item) { return $item['autor']; }
    );
    require ('php/pagina_lista.php');
