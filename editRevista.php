<?php
    // Detalhes e edição de uma revista (o visual e a lógica ficam em php/pagina_editar.php)
    $cfg = array(
        'tabela' => 'revistas',
        'menu' => 'revistas',
        'nome' => 'Revista',
        'genero' => 'a',
        'pagina' => 'editRevista.php',
        'acaoVer' => 'editarRevista',
        'acaoEmprest' => 'emprestRevista',
        'voltar' => 'revistas.php',
        'rotuloSituacao' => 'Situação da Revista',
        'situacoes' => array(0 => 'Estante', 1 => 'Emprestada', 2 => 'Extraviada'),
        // coluna => (rótulo, ocupa a linha inteira?)
        'ficha' => array(
            'revista' => array('Revista', true),
            'titulo' => array('Título', true),
            'numero' => array('Número'),
            'ano' => array('Ano'),
            'autor' => array('Autor'),
            'editora' => array('Editora'),
            'issn' => array('ISSN'),
            'compra' => array('Compra')
        ),
        'campos' => array(
            'revista' => array('rotulo' => 'Nome da Revista', 'inteiro' => true),
            'titulo' => array('rotulo' => 'Título', 'inteiro' => true),
            'numero' => array('rotulo' => 'Número', 'tipo' => 'number'),
            'ano' => array('rotulo' => 'Ano', 'tipo' => 'number'),
            'autor' => array('rotulo' => 'Autor'),
            'editora' => array('rotulo' => 'Editora'),
            'issn' => array('rotulo' => 'ISSN'),
            'compra' => array('rotulo' => 'Data da Compra', 'tipo' => 'date')
        ),
        'tituloItem' => function ($reg) { return $reg['revista']." nº ".$reg['numero']; },
        'alterar' => function ($idUser, $codigo, $post, $capa, $ebook, $arqEmprestar, $arqMorto) {
            $altera = new EditRevistas(
                $idUser, "revistas",
                $post['revista'] ?? '', $post['numero'] ?? '', $post['titulo'] ?? '',
                $post['autor'] ?? '', $post['editora'] ?? '', $post['ano'] ?? '', $post['issn'] ?? '',
                $post['compra'] ?? '',
                $capa['tmp_name'], $capa['size'], pathinfo($capa['name'], PATHINFO_EXTENSION),
                $post['sinopse'] ?? '', $post['opiniao'] ?? '',
                $ebook, $arqEmprestar, $arqMorto
            );
            $altera->altRevistas($codigo);
        }
    );
    require ('php/pagina_editar.php');
