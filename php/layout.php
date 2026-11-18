<?php
    // Partes visuais comuns a todas as páginas (menu lateral, cabeçalho e rodapé).
    // Uso em uma página:
    //     abrirPagina("Livros", "livros");      // título da aba e item ativo do menu
    //     boasVindas();                         // painel "Bem-vindo, Nome" + data
    //     ... conteúdo da página ...
    //     fecharPagina();

    // Ícones (traço simples, herdam a cor do texto)
    function icone($nome) {
        $desenhos = array(
            'inicio'    => '<path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
            'livros'    => '<path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/>',
            'revistas'  => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
            'forum'     => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
            'analise'   => '<path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>',
            'sair'      => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'entrar'    => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>',
            'cadastrar' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/>',
            'voltar'    => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
            'mais'      => '<path d="M12 5v14"/><path d="M5 12h14"/>'
        );
        return '<svg class="icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($desenhos[$nome] ?? '').'</svg>';
    }

    // Abre o HTML, desenha o menu lateral e inicia a área de conteúdo.
    // $ativo: inicio | livros | revistas | forum | analise | entrar | cadastrar
    // $logado: false mostra o menu de visitante (Início, Entrar, Cadastrar)
    function abrirPagina($titulo, $ativo, $logado = true) {
        if ($logado) {
            $itens = array(
                'inicio'   => array('inicio.php', 'Início'),
                'livros'   => array('livros.php', 'Livros'),
                'revistas' => array('revistas.php', 'Revistas'),
                'forum'    => array('forum.php', 'Fórum')
            );
            if (!empty($_SESSION['admin'])) {
                $itens['analise'] = array('analise.php', 'Análise');
            }
        }
        else {
            $itens = array(
                'inicio'    => array('index.html', 'Início'),
                'entrar'    => array('login.php', 'Entrar'),
                'cadastrar' => array('cadastrar.php', 'Cadastrar')
            );
        }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=h($titulo)?> · Estante Virtual</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&display=swap">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<div class="app">
    <aside class="lateral">
        <a class="marca" href="<?=$logado ? 'inicio.php' : 'index.html'?>">
            <span class="marca-logo" aria-hidden="true"><span></span><span></span></span>
            <span>Estante<br>Virtual</span>
        </a>
        <nav class="menu" aria-label="Menu principal">
            <?php foreach ($itens as $chave => $item) { ?>
            <a href="<?=$item[0]?>" class="<?=$chave === $ativo ? 'ativo' : ''?>"<?=$chave === $ativo ? ' aria-current="page"' : ''?>><?=icone($chave)?><?=$item[1]?></a>
            <?php } ?>
        </nav>
        <?php if ($logado) { ?>
        <a class="sair" href="?acao=logout"><?=icone('sair')?>Sair</a>
        <?php } ?>
    </aside>
    <main class="conteudo">
<?php
    }

    // Painel de cabeçalho com o nome do usuário e a data (preenchida pelo js/funcao.js)
    function boasVindas($titulo = null) {
        $nome = $_SESSION['nome'] ?? ($_SESSION['user'] ?? '');
        $texto = $titulo ?? ("Bem-vindo, ".$nome);
?>
        <header class="painel topo">
            <h1><?=h($texto)?></h1>
            <p id="idData" class="data"></p>
        </header>
<?php
    }

    // Prateleira vazia decorativa (livros tracejados). $link: destino do "+"
    function prateleiraVazia($link) {
        $alturas = array(
            array(122, 110, 94, 122, 102, 116, 88, 108, 98, 118, 104, 114, 96),
            array(106, 118, 92, 112, 100, 120, 94, 108, 116, 98)
        );
        foreach ($alturas as $linha => $livros) {
            echo '<div class="prateleira-vazia" aria-hidden="true">';
            foreach ($livros as $i => $altura) {
                $larg = 34 + (($i * 7 + $linha * 3) % 4) * 6;
                if ($linha === 0 && $i === 0) {
                    echo '<a class="lombada nova" href="'.h($link).'" style="height:'.$altura.'px;width:44px" tabindex="-1">'.icone('mais').'</a>';
                }
                else {
                    echo '<span class="lombada" style="height:'.$altura.'px;width:'.$larg.'px"></span>';
                }
            }
            echo '</div>';
        }
    }

    // Monta a barra de páginas. $base: URL sem o número da página, terminando em "pagina="
    function paginacao($pagina, $totalPaginas, $base) {
        if ($totalPaginas <= 1) {
            return;
        }
        $ant = max(1, $pagina - 1);
        $prox = min($totalPaginas, $pagina + 1);
?>
        <nav class="paginacao" aria-label="Páginas">
            <a href="<?=h($base.'1')?>" aria-label="Primeira página">&laquo;</a>
            <a href="<?=h($base.$ant)?>" aria-label="Página anterior">&lsaquo;</a>
            <span class="atual">Página <?=$pagina?> de <?=$totalPaginas?></span>
            <a href="<?=h($base.$prox)?>" aria-label="Próxima página">&rsaquo;</a>
            <a href="<?=h($base.$totalPaginas)?>" aria-label="Última página">&raquo;</a>
        </nav>
<?php
    }

    // Fecha a área de conteúdo e o HTML. $scripts: JS extras da página
    function fecharPagina($scripts = array()) {
?>
        <footer class="rodape">Desenvolvido por Gustavo Coscarello</footer>
    </main>
</div>
<?php foreach ($scripts as $script) { ?>
<script src="<?=h($script)?>"></script>
<?php } ?>
<script src="js/funcao.js"></script>
</body>
</html>
<?php
    }
?>
