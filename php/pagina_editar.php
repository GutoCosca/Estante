<?php
    // Tela de detalhes/edição usada por editLivro.php e editRevista.php.
    // A página que inclui este arquivo define $cfg com o que muda entre livros e revistas.
    require_once ('php/funcao.php');
    require_once ('php/forum.php');
    exigirLogin();

    $idUser = (int)$_SESSION['id_user'];
    $codigo = (int)($_REQUEST['buscaCodigo'] ?? 0);
    $ehLivro = ($cfg['tabela'] === 'livros');
    $self = $cfg['pagina'].'?acao='.$cfg['acaoVer'].'&buscaCodigo='.$codigo;

    // Busca o registro (só encontra se for do usuário logado)
    $dados = new Registros($idUser, $cfg['tabela'], "", "", "", "", "", "", "");
    $dados->buscar($codigo);
    $reg = mysqli_fetch_assoc($dados->getTbl());

    if (!$reg) {
        abrirPagina($cfg['nome'], $cfg['menu']);
        boasVindas();
?>
        <section class="painel painel-grande">
            <h2><?=$cfg['nome']?> não encontrad<?=$cfg['genero']?></h2>
            <p class="subtitulo">Ele pode ter sido removido ou pertencer a outro usuário.</p>
            <div class="botoes" style="max-width:360px">
                <a class="botao botao-primario" href="<?=$cfg['voltar']?>"><?=icone('voltar')?>Voltar à estante</a>
            </div>
        </section>
<?php
        fecharPagina();
        exit;
    }

    // ---------- Ações (POST) ----------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (($_REQUEST['acao'] ?? '') === 'alterar') {
            $ebook = (($_POST['ebook'] ?? '') == 1) ? 1 : 0;
            $situacao = (int)($_POST['situacao'] ?? 0);
            // eBook não é emprestado nem extraviado
            $arqEmprestar = (!$ebook && $situacao === 1) ? 1 : 0;
            $arqMorto = (!$ebook && $situacao === 2) ? 1 : 0;
            $capa = $_FILES['capa'] ?? array('tmp_name' => '', 'size' => 0, 'name' => '');
            $cfg['alterar']($idUser, $codigo, $_POST, $capa, $ebook, $arqEmprestar, $arqMorto);
            header('location:'.$self.'&salvo=1');
            exit;
        }
        if (isset($_POST['topico'])) {
            $forum = new Forum($idUser, $codigo, $cfg['acaoVer'], $_POST['topico'], $_POST['detalhe'] ?? '', "", "");
            $forum->abrir();
            header('location:'.$self.'&forum=1');
            exit;
        }
    }

    // ---------- Situação e empréstimo atual ----------
    if ($reg['ebook'] == 1) {
        $selo = array('ebook', 'eBook');
    }
    elseif ($reg['arqempresta'] == 1) {
        $selo = array('emprestado', $cfg['situacoes'][1]);
    }
    elseif ($reg['arqmorto'] == 1) {
        $selo = array('extraviado', $cfg['situacoes'][2]);
    }
    else {
        $selo = array('', 'Na estante');
    }
    $situacaoAtual = ($reg['arqempresta'] == 1) ? 1 : (($reg['arqmorto'] == 1) ? 2 : 0);

    $emprest = new Emprestar($idUser, "", $ehLivro ? $codigo : "", $ehLivro ? "" : $codigo, "", "", "", 1);
    $emprest->listar();
    $ultimo = mysqli_fetch_assoc($emprest->getTbl());
    $emprestadoAgora = ($ultimo && $ultimo['dt_devol'] == null) ? $ultimo : null;

    $titulo = $cfg['tituloItem']($reg);

    abrirPagina($titulo, $cfg['menu']);
    boasVindas();
?>
        <div class="grade-2 grade-larga">
            <!-- Ficha -->
            <section class="painel">
                <div class="painel-titulo">
                    <h2>Dados d<?=$cfg['genero']?> <?=$cfg['nome']?></h2>
                    <span class="selo <?=$selo[0]?>"><?=$selo[1]?></span>
                </div>
                <?php if (isset($_GET['salvo'])) { ?>
                <p class="mensagem" style="margin-bottom:18px">Alterações salvas.</p>
                <?php } ?>
                <div class="ficha">
                    <?php if ($reg['capa'] != null) { ?>
                    <div class="capa-grande"><img src="capas/<?=h($reg['capa'])?>" alt="Capa de <?=h($titulo)?>"></div>
                    <?php } else { ?>
                    <div class="capa-grande sem-capa cor-<?=$codigo % 6?>"><?=h($titulo)?></div>
                    <?php } ?>
                    <dl class="dados">
                        <?php foreach ($cfg['ficha'] as $coluna => $info) {
                            $valor = $reg[$coluna];
                            if ($coluna === 'compra' && $valor != null) {
                                $valor = mesBR($valor)[1];
                            }
                        ?>
                        <div<?=!empty($info[1]) ? ' class="inteiro"' : ''?>>
                            <dt><?=$info[0]?></dt>
                            <dd><?=($valor === null || $valor === '') ? '<span class="vazio">—</span>' : h($valor)?></dd>
                        </div>
                        <?php } ?>
                    </dl>
                </div>
                <div class="bloco-texto">
                    <h3>Sinopse</h3>
                    <p class="texto"><?=$reg['sinopse'] != '' ? h($reg['sinopse']) : '<span class="vazio">Sem sinopse.</span>'?></p>
                    <h3>Opinião</h3>
                    <p class="texto"><?=$reg['opiniao'] != '' ? h($reg['opiniao']) : '<span class="vazio">Sem opinião.</span>'?></p>
                </div>
            </section>

            <div class="pilha">
                <!-- Empréstimo -->
                <section class="painel">
                    <h2>Empréstimo</h2>
                    <?php if ($reg['ebook'] == 1) { ?>
                    <p class="subtitulo">eBooks não são emprestados.</p>
                    <?php } else { ?>
                    <dl class="dados">
                        <div>
                            <dt>Com</dt>
                            <dd><?=$emprestadoAgora ? h($emprestadoAgora['nome']) : '<span class="vazio">Ninguém</span>'?></dd>
                        </div>
                        <div>
                            <dt>Desde</dt>
                            <dd><?=$emprestadoAgora ? mesBR($emprestadoAgora['dt_emprest'])[1] : '<span class="vazio">—</span>'?></dd>
                        </div>
                    </dl>
                    <div class="botoes" style="grid-template-columns:1fr">
                        <a class="botao botao-escuro" href="emprestar.php?acao=<?=$cfg['acaoEmprest']?>&buscaCodigo=<?=$codigo?>">Emprestar e ver histórico</a>
                    </div>
                    <?php } ?>
                </section>

                <!-- Criar fórum -->
                <section class="painel">
                    <h2>Criar Fórum</h2>
                    <?php if (isset($_GET['forum'])) { ?>
                    <p class="mensagem" style="margin-bottom:18px">Fórum criado. <a href="forum.php">Ver fóruns</a></p>
                    <?php } ?>
                    <form action="<?=h($self)?>" method="post">
                        <div class="campos">
                            <div class="campo inteiro">
                                <label for="idTopico" class="obrig">Tópico</label>
                                <input type="text" name="topico" id="idTopico" maxlength="250" required>
                            </div>
                            <div class="campo inteiro">
                                <label for="idDetalhe">Detalhe</label>
                                <textarea name="detalhe" id="idDetalhe" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="botoes">
                            <button type="submit" class="botao botao-primario">Criar</button>
                            <button type="reset" class="botao botao-secundario">Limpar</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <!-- Alterar -->
        <section class="painel">
            <h2>Alterar Dados d<?=$cfg['genero']?> <?=$cfg['nome']?></h2>
            <form action="<?=$cfg['pagina']?>?acao=alterar&buscaCodigo=<?=$codigo?>" method="post" enctype="multipart/form-data">
                <div class="campos">
                    <div class="campo">
                        <label for="idCapa">Capa (jpg, png, gif ou webp até 5 MB)</label>
                        <input type="file" name="capa" id="idCapa" accept="image/jpeg,image/png,image/gif,image/webp">
                    </div>
                    <div class="campo" style="justify-content:flex-end">
                        <label class="marcar"><input type="checkbox" name="ebook" value="1" id="idEbook"<?=$reg['ebook'] == 1 ? ' checked' : ''?>> É um eBook</label>
                    </div>
                    <?php foreach ($cfg['campos'] as $nome => $campo) { ?>
                    <div class="campo<?=!empty($campo['inteiro']) ? ' inteiro' : ''?>">
                        <label for="idAlt_<?=$nome?>"><?=$campo['rotulo']?></label>
                        <input type="<?=$campo['tipo'] ?? 'text'?>" name="<?=$nome?>" id="idAlt_<?=$nome?>" value="<?=h($reg[$nome])?>">
                    </div>
                    <?php } ?>
                    <div class="campo inteiro">
                        <label for="idSinopse">Sinopse</label>
                        <textarea name="sinopse" id="idSinopse" maxlength="1000"><?=h($reg['sinopse'])?></textarea>
                    </div>
                    <div class="campo inteiro">
                        <label for="idOpiniao">Opinião</label>
                        <textarea name="opiniao" id="idOpiniao" maxlength="1000"><?=h($reg['opiniao'])?></textarea>
                    </div>
                    <fieldset class="campo inteiro" id="idSituacao" style="border:none">
                        <legend class="rotulo"><?=$cfg['rotuloSituacao']?></legend>
                        <div class="opcoes">
                            <?php foreach ($cfg['situacoes'] as $valor => $rotulo) { ?>
                            <label class="opcao"><input type="radio" name="situacao" value="<?=$valor?>"<?=$valor === $situacaoAtual ? ' checked' : ''?>><span><?=$rotulo?></span></label>
                            <?php } ?>
                        </div>
                    </fieldset>
                </div>
                <div class="botoes" style="max-width:520px">
                    <button type="submit" class="botao botao-primario">Salvar alterações</button>
                    <a href="<?=h($self)?>" class="botao botao-secundario">Cancelar</a>
                </div>
            </form>
        </section>
        <script>
            // eBook não tem situação (estante/emprestado/extraviado)
            (function () {
                const ebook = document.querySelector('#idEbook');
                const situacao = document.querySelector('#idSituacao');
                function atualizar() {
                    situacao.disabled = ebook.checked;
                    situacao.style.opacity = ebook.checked ? '0.45' : '1';
                }
                ebook.addEventListener('change', atualizar);
                atualizar();
            })();
        </script>
<?php
    fecharPagina();
?>
