<?php
    // Tela de estante usada por livros.php e revistas.php.
    // A página que inclui este arquivo define $cfg com o que muda entre livros e revistas.
    require_once ('php/funcao.php');
    exigirLogin();

    $idUser = (int)$_SESSION['id_user'];
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $porPagina = 10;
    $ehLivro = ($cfg['tabela'] === 'livros');
    $paginaAtual = $cfg['menu'].'.php';

    // ---------- Adicionar (POST) ----------
    if (($_REQUEST['acao'] ?? '') === $cfg['acaoAdd'] && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $principal = trim($_POST[$cfg['campoPrincipal']] ?? '');
        if ($principal !== '') {
            if ($ehLivro) {
                $add = new EditLivros(
                    $idUser, "livros",
                    $_POST['livro'] ?? '', $_POST['autor'] ?? '', $_POST['editora'] ?? '',
                    "", "", "",
                    $_POST['compra'] ?? '',
                    "", "", "", "", "",
                    0, 0, 0
                );
                $add->addLivros();
            }
            else {
                $add = new EditRevistas(
                    $idUser, "revistas",
                    $_POST['revista'] ?? '', $_POST['numero'] ?? '', $_POST['titulo'] ?? '',
                    "", "", "", "",
                    $_POST['compra'] ?? '',
                    "", "", "", "", "",
                    0, 0, 0
                );
                $add->addRevistas();
            }
        }
        // redireciona para não adicionar de novo ao atualizar a página
        header('location:'.$paginaAtual.'?adicionado=1');
        exit;
    }

    // ---------- Busca (GET) ----------
    $buscando = (($_GET['acao'] ?? '') === 'Buscar');
    $letra = $buscando ? trim($_GET['letra'] ?? '') : '';
    $tipo = $buscando ? ($_GET['tipo'] ?? '') : '';
    if (!array_key_exists($tipo, $cfg['tipos'])) {
        $tipo = array_key_first($cfg['tipos']);
    }
    $ordem = ($buscando && ($_GET['ordem'] ?? '') === 'DESC') ? 'DESC' : 'ASC';
    $condic = $buscando ? (int)($_GET['condic'] ?? 0) : 0;
    if ($condic < 0 || $condic > 2) {
        $condic = 0;
    }
    $arqEmprestar = ($condic === 1) ? 1 : 0;
    $arqMorto = ($condic === 2) ? 1 : 0;

    $listar = new Registros(
        $idUser, $cfg['tabela'], $tipo, $letra, $ordem,
        0, $arqEmprestar, $arqMorto,
        ($pagina - 1) * $porPagina
    );
    $listar->total();
    $total = (int)mysqli_fetch_array($listar->getTbl())[0];
    $totalPaginas = max(1, (int)ceil($total / $porPagina));
    if ($total > 0) {
        $listar->lista();
    }

    // URL da paginação mantendo os filtros da busca
    $filtros = $_GET;
    unset($filtros['pagina'], $filtros['adicionado']);
    $basePaginas = $paginaAtual.'?'.http_build_query($filtros).(count($filtros) > 0 ? '&' : '').'pagina=';

    $tituloLista = $cfg['titulos'][$condic];
    $filtrado = ($letra !== '' || $condic !== 0);

    abrirPagina($cfg['plural'], $cfg['menu']);
    boasVindas();
?>
        <div class="grade-2">
            <!-- Busca -->
            <section class="painel">
                <h2>Localizar <?=$cfg['nome']?></h2>
                <form action="<?=$paginaAtual?>" method="get">
                    <div class="campos">
                        <div class="campo inteiro">
                            <label for="idLetra">Nome</label>
                            <input type="text" name="letra" id="idLetra" value="<?=h($letra)?>">
                        </div>
                        <div class="campo">
                            <label for="idTipo">Ordenar por</label>
                            <select name="tipo" id="idTipo">
                                <?php foreach ($cfg['tipos'] as $valor => $rotulo) { ?>
                                <option value="<?=$valor?>"<?=$valor === $tipo ? ' selected' : ''?>><?=$rotulo?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="idOrdem">Ordem</label>
                            <select name="ordem" id="idOrdem">
                                <option value="ASC"<?=$ordem === 'ASC' ? ' selected' : ''?>>Crescente</option>
                                <option value="DESC"<?=$ordem === 'DESC' ? ' selected' : ''?>>Decrescente</option>
                            </select>
                        </div>
                        <div class="campo inteiro">
                            <label for="idCondic"><?=$cfg['rotuloSituacao']?></label>
                            <select name="condic" id="idCondic">
                                <?php foreach ($cfg['situacoes'] as $valor => $rotulo) { ?>
                                <option value="<?=$valor?>"<?=$valor === $condic ? ' selected' : ''?>><?=$rotulo?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="botoes">
                        <button type="submit" name="acao" value="Buscar" class="botao botao-primario">Buscar</button>
                        <a href="<?=$paginaAtual?>" class="botao botao-secundario">Limpar</a>
                    </div>
                </form>
            </section>

            <!-- Adicionar -->
            <section class="painel">
                <div class="painel-titulo">
                    <h2>Adicionar <?=$cfg['nome']?></h2>
                    <span class="aviso-obrig">*campos obrigatórios</span>
                </div>
                <form action="<?=$paginaAtual?>?acao=<?=$cfg['acaoAdd']?>" method="post">
                    <div class="campos">
                        <?php foreach ($cfg['campos'] as $nome => $campo) { ?>
                        <div class="campo<?=!empty($campo['inteiro']) ? ' inteiro' : ''?>">
                            <label for="idAdd_<?=$nome?>"<?=!empty($campo['obrig']) ? ' class="obrig"' : ''?>><?=$campo['rotulo']?></label>
                            <input type="<?=$campo['tipo'] ?? 'text'?>" name="<?=$nome?>" id="idAdd_<?=$nome?>"<?=!empty($campo['obrig']) ? ' required' : ''?><?=($campo['tipo'] ?? '') === 'number' ? ' min="0"' : ''?>>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="botoes">
                        <button type="submit" class="botao botao-primario">Adicionar</button>
                        <button type="reset" class="botao botao-secundario">Limpar</button>
                    </div>
                </form>
            </section>
        </div>

        <!-- Estante -->
        <section class="painel painel-grande">
            <?php if (isset($_GET['adicionado'])) { ?>
            <p class="mensagem" style="margin-bottom:18px"><?=$cfg['nome']?> adicionad<?=$cfg['genero']?> à estante.</p>
            <?php } ?>

            <?php if ($total > 0) { ?>
            <h2><?=$tituloLista?></h2>
            <p class="subtitulo"><?=$total?> <?=$total === 1 ? mb_strtolower($cfg['nome']) : mb_strtolower($cfg['plural'])?><?=$filtrado ? ' encontrad'.$cfg['genero'].($total === 1 ? '' : 's') : ''?></p>
            <div class="prateleira" style="margin-top:28px">
                <?php
                    while ($item = mysqli_fetch_array($listar->getTbl())) {
                        $id = (int)$item[$cfg['id']];
                        $titulo = $cfg['tituloItem']($item);
                        $detalhe = $cfg['detalheItem']($item);
                ?>
                <a class="item-estante" href="<?=$cfg['editar'].$id?>" title="<?=h($titulo)?>">
                    <?php if ($item['capa'] != null) { ?>
                    <div class="capa"><img src="capas/<?=h($item['capa'])?>" alt="Capa de <?=h($titulo)?>" loading="lazy"></div>
                    <?php } else { ?>
                    <div class="capa sem-capa cor-<?=$id % 6?>"><?=h($titulo)?></div>
                    <?php } ?>
                    <p class="titulo"><?=h($titulo)?></p>
                    <p class="detalhe"><?=h($detalhe)?></p>
                </a>
                <?php } ?>
            </div>
            <?php paginacao($pagina, $totalPaginas, $basePaginas); ?>

            <?php } elseif ($filtrado) { ?>
            <h2>Nenhum<?=$cfg['genero'] === 'a' ? 'a' : ''?> <?=mb_strtolower($cfg['nome'])?> encontrad<?=$cfg['genero']?></h2>
            <p class="subtitulo">Tente outro nome ou outra situação na busca.</p>
            <?php prateleiraVazia('#idLetra'); ?>

            <?php } else { ?>
            <h2><?=$cfg['titulos'][0]?> Está Vazia</h2>
            <p class="subtitulo">Preencha o formulário acima para adicionar <?=$cfg['genero'] === 'a' ? 'a primeira' : 'o primeiro'?> <?=mb_strtolower($cfg['nome'])?>.</p>
            <?php prateleiraVazia('#idAdd_'.$cfg['campoPrincipal']); ?>
            <?php } ?>
        </section>
<?php
    fecharPagina();
?>
