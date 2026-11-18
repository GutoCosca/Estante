<?php
    // Estante de revistas (o visual e a lógica ficam em php/pagina_lista.php)
    $cfg = array(
        'tabela' => 'revistas',
        'id' => 'id_revistas',
        'menu' => 'revistas',
        'nome' => 'Revista',
        'plural' => 'Revistas',
        'genero' => 'a',
        'acaoAdd' => 'addRevista',
        'campoPrincipal' => 'revista',
        'editar' => 'editRevista.php?acao=editarRevista&buscaCodigo=',
        'rotuloSituacao' => 'Situação da Revista',
        'tipos' => array('revista' => 'Revista', 'titulo' => 'Título'),
        'situacoes' => array(0 => 'Estante', 1 => 'Emprestada', 2 => 'Extraviada'),
        'titulos' => array('Sua Estante de Revistas', 'Suas Revistas Emprestadas', 'Suas Revistas Extraviadas'),
        'campos' => array(
            'revista' => array('rotulo' => 'Nome da Revista', 'obrig' => true, 'inteiro' => true),
            'numero'  => array('rotulo' => 'Número', 'obrig' => true, 'tipo' => 'number'),
            'compra'  => array('rotulo' => 'Data da Compra', 'tipo' => 'date'),
            'titulo'  => array('rotulo' => 'Título', 'inteiro' => true)
        ),
        'tituloItem' => function ($item) { return $item['revista']." nº ".$item['numero']; },
        'detalheItem' => function ($item) { return $item['titulo']; }
    );
    require ('php/pagina_lista.php');
