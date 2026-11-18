<?php
    require_once ('php/funcao.php');
    require_once ('php/forum.php');
    exigirLogin();

    $idUser = (int)$_SESSION['id_user'];
    $porPagina = 8;

    // Filtros atuais (mantidos nos links)
    $estado = array(
        'forum' => (($_GET['forum'] ?? '') === 'outros') ? 'outros' : 'pessoal',
        'condicao' => (($_GET['condicao'] ?? '') === 'fechados') ? 'fechados' : 'abertos',
        'pagForum' => isset($_GET['pagForum']) ? max(1, (int)$_GET['pagForum']) : 1
    );
    $topicoId = isset($_GET['topico']) ? (int)$_GET['topico'] : null;

    // Monta um link do fórum trocando só o que for informado
    function linkForum($estado, $mudar = array(), $topico = null) {
        $parametros = array_merge($estado, $mudar);
        if ($topico) {
            $parametros['topico'] = $topico;
        }
        return 'forum.php?'.http_build_query($parametros);
    }

    // Nome e capa do livro/revista de uma pergunta
    function periodicoDaPergunta($pergunta) {
        if ($pergunta['id_livros'] != null) {
            $registro = new Registros("", "livros", "", "", "", "", "", "", "");
            $registro->buscar($pergunta['id_livros']);
            $dados = mysqli_fetch_assoc($registro->getTbl());
            return array('Livro', $dados ? $dados['livro'] : '', $dados['capa'] ?? null, $dados ? ('Autor: '.$dados['autor']) : '');
        }
        if ($pergunta['id_revistas'] != null) {
            $registro = new Registros("", "revistas", "", "", "", "", "", "", "");
            $registro->buscar($pergunta['id_revistas']);
            $dados = mysqli_fetch_assoc($registro->getTbl());
            return array('Revista', $dados ? ($dados['revista'].' nº '.$dados['numero']) : '', $dados['capa'] ?? null, $dados ? $dados['titulo'] : '');
        }
        return array('', '', null, '');
    }

    $fechar = ($estado['condicao'] === 'fechados') ? 1 : 0;
    $filtroUsuario = ($estado['forum'] === 'outros') ? "<> ".$idUser : "= ".$idUser;

    $lista = new Forum($filtroUsuario, "", "", "", "", $fechar, ($estado['pagForum'] - 1) * $porPagina);
    $lista->total();
    $total = (int)mysqli_fetch_array($lista->getTbl())[0];
    $totalPaginas = max(1, (int)ceil($total / $porPagina));
    $perguntas = array();
    if ($total > 0) {
        $lista->listar();
        while ($linha = mysqli_fetch_assoc($lista->getTbl())) {
            $linha['periodico'] = periodicoDaPergunta($linha);
            $perguntas[] = $linha;
        }
    }

    // Tópico aberto
    $aberto = null;
    if ($topicoId) {
        $busca = new Forum("", "", "", "", "", "", "");
        $busca->busca($topicoId);
        $aberto = mysqli_fetch_assoc($busca->getTbl());
        if ($aberto) {
            $aberto['periodico'] = periodicoDaPergunta($aberto);
        }
    }

    $titulo = (($estado['forum'] === 'outros') ? 'Outros Fóruns ' : 'Meus Fóruns ').(($fechar === 1) ? 'Fechados' : 'Abertos');

    abrirPagina('Fórum', 'forum');
    boasVindas();
?>
        <div class="grade-2 grade-larga">
            <!-- Lista de tópicos -->
            <section class="painel">
                <nav class="abas" aria-label="De quem">
                    <a class="aba<?=$estado['forum'] === 'pessoal' ? ' ativa' : ''?>" href="<?=h(linkForum($estado, array('forum' => 'pessoal', 'pagForum' => 1)))?>">Meus fóruns</a>
                    <a class="aba<?=$estado['forum'] === 'outros' ? ' ativa' : ''?>" href="<?=h(linkForum($estado, array('forum' => 'outros', 'pagForum' => 1)))?>">Outros fóruns</a>
                </nav>
                <nav class="abas" aria-label="Situação">
                    <a class="aba<?=$fechar === 0 ? ' ativa' : ''?>" href="<?=h(linkForum($estado, array('condicao' => 'abertos', 'pagForum' => 1)))?>">Abertos</a>
                    <a class="aba<?=$fechar === 1 ? ' ativa' : ''?>" href="<?=h(linkForum($estado, array('condicao' => 'fechados', 'pagForum' => 1)))?>">Fechados</a>
                </nav>
                <h2 style="margin-bottom:16px"><?=$titulo?></h2>

                <?php if ($total > 0) { ?>
                <ul class="topicos">
                    <?php foreach ($perguntas as $p) {
                        $per = $p['periodico'];
                        $id = (int)$p['id_pergunta'];
                    ?>
                    <li>
                        <a class="topico<?=$id === $topicoId ? ' selecionado' : ''?>" href="<?=h(linkForum($estado, array(), $id))?>">
                            <?php if ($per[2] != null) { ?>
                            <span class="miniatura"><img src="capas/<?=h($per[2])?>" alt=""></span>
                            <?php } else { ?>
                            <span class="miniatura sem-capa cor-<?=$id % 6?>"></span>
                            <?php } ?>
                            <span>
                                <span class="periodico"><?=$per[0]?><?=$per[1] !== '' ? ' · '.h($per[1]) : ''?></span><br>
                                <span class="assunto"><?=h($p['topico'])?></span>
                            </span>
                            <span class="quando">
                                <?=date('d/m/Y', strtotime($p['dt_aberta']))?><br><?=substr($p['hr_aberta'], 0, 5)?>
                                <?php if ($fechar === 1 && $p['dt_fecha'] != null) { ?>
                                <br>Fechado em <?=date('d/m/Y', strtotime($p['dt_fecha']))?>
                                <?php } ?>
                            </span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php paginacao($estado['pagForum'], $totalPaginas, linkForum($estado, array('pagForum' => '')) ); ?>
                <?php } else { ?>
                <p class="subtitulo">Não há fóruns aqui. Para criar um, abra um livro ou revista da sua estante.</p>
                <?php } ?>
            </section>

            <!-- Tópico selecionado -->
            <section class="painel discussao">
                <?php if ($aberto) { $per = $aberto['periodico']; ?>
                <p class="selo"><?=$per[0] !== '' ? $per[0] : 'Tópico'?></p>
                <h2 style="margin:12px 0 4px"><?=h($aberto['topico'])?></h2>
                <?php if ($per[1] !== '') { ?>
                <p class="subtitulo"><?=h($per[1])?><?=$per[3] !== '' ? ' — '.h($per[3]) : ''?></p>
                <?php } ?>
                <div class="bloco-texto">
                    <p class="texto"><?=$aberto['detalhe'] != '' ? h($aberto['detalhe']) : '<span class="vazio">Sem detalhes.</span>'?></p>
                </div>
                <p class="meta">Por <strong><?=h($aberto['nome'])?></strong> em <?=date('d/m/Y', strtotime($aberto['dt_aberta']))?> às <?=substr($aberto['hr_aberta'], 0, 5)?></p>
                <?php } elseif ($topicoId) { ?>
                <h2>Tópico não encontrado</h2>
                <p class="subtitulo">Escolha outro tópico na lista.</p>
                <?php } else { ?>
                <h2>Nenhum tópico selecionado</h2>
                <p class="subtitulo">Escolha um tópico na lista para ler a discussão.</p>
                <?php } ?>
            </section>
        </div>
<?php
    fecharPagina();
?>
