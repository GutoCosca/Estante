<?php
    require_once ('php/funcao.php');
    exigirLogin();

    $idUser = (int)$_SESSION['id_user'];
    $acao = (($_REQUEST['acao'] ?? '') === 'emprestRevista') ? 'emprestRevista' : 'emprestLivro';
    $ehLivro = ($acao === 'emprestLivro');
    $codigo = (int)($_REQUEST['buscaCodigo'] ?? 0);
    $idNome = isset($_REQUEST['idNome']) ? (int)$_REQUEST['idNome'] : null;
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $porPagina = 20;

    $nome = $ehLivro ? 'Livro' : 'Revista';
    $genero = $ehLivro ? 'o' : 'a';
    $menu = $ehLivro ? 'livros' : 'revistas';
    $voltar = $ehLivro ? "editLivro.php?acao=editarLivro&buscaCodigo=".$codigo : "editRevista.php?acao=editarRevista&buscaCodigo=".$codigo;
    $self = "emprestar.php?acao=".$acao."&buscaCodigo=".$codigo;
    $idLivro = $ehLivro ? $codigo : "";
    $idRevista = $ehLivro ? "" : $codigo;

    // Busca o livro/revista (só encontra se for do usuário logado)
    $dados = new Registros($idUser, $ehLivro ? "livros" : "revistas", "", "", "", "", "", "", "");
    $dados->buscar($codigo);
    $reg = mysqli_fetch_assoc($dados->getTbl());

    if (!$reg) {
        abrirPagina('Empréstimos', $menu);
        boasVindas();
?>
        <section class="painel painel-grande">
            <h2><?=$nome?> não encontrad<?=$genero?></h2>
            <p class="subtitulo">Ele pode ter sido removido ou pertencer a outro usuário.</p>
            <div class="botoes" style="max-width:360px">
                <a class="botao botao-primario" href="<?=$ehLivro ? 'livros.php' : 'revistas.php'?>"><?=icone('voltar')?>Voltar à estante</a>
            </div>
        </section>
<?php
        fecharPagina();
        exit;
    }

    // ---------- Adicionar / alterar (POST) ----------
    $mensagem = "";
    $erro = false;
    if (isset($_POST['emprestar'])) {
        if ($_POST['emprestar'] === "Adicionar") {
            $novo = new Emprestar($idUser, "", $idLivro, $idRevista, $_POST['nome'] ?? '', $_POST['sair'] ?? '', $_POST['entrar'] ?? '', "");
            $mensagem = $novo->addEmprest();
            if ($mensagem === "Empréstimo adicionado") {
                $novo->sincronizarSituacao();
                header('location:'.$self.'&aviso=adicionado');
                exit;
            }
        }
        elseif ($_POST['emprestar'] === "Alterar") {
            $altera = new Emprestar($idUser, $idNome, $idLivro, $idRevista, $_POST['nome'] ?? '', $_POST['sair'] ?? '', $_POST['entrar'] ?? '', "");
            $mensagem = $altera->altEmprest();
            if ($mensagem === "Empréstimo alterado") {
                $altera->sincronizarSituacao();
                header('location:'.$self.'&idNome='.$idNome.'&aviso=alterado');
                exit;
            }
        }
        $erro = true;
    }
    if (isset($_GET['aviso'])) {
        $mensagem = ($_GET['aviso'] === 'alterado') ? "Empréstimo alterado." : "Empréstimo adicionado.";
    }

    // Empréstimo selecionado na lista (para alterar)
    $selecionado = null;
    if ($idNome) {
        $busca = new Emprestar($idUser, $idNome, $idLivro, $idRevista, "", "", "", "");
        $busca->listar();
        $selecionado = mysqli_fetch_assoc($busca->getTbl());
    }
    // em caso de erro, mantém o que foi digitado
    $form = array(
        'sair' => $erro ? ($_POST['sair'] ?? '') : ($selecionado['dt_emprest'] ?? ''),
        'entrar' => $erro ? ($_POST['entrar'] ?? '') : ($selecionado['dt_devol'] ?? ''),
        'nome' => $erro ? ($_POST['nome'] ?? '') : ($selecionado['nome'] ?? '')
    );

    // Histórico
    $historico = new Emprestar($idUser, "", $idLivro, $idRevista, "", "", "", (($pagina - 1) * $porPagina).",".$porPagina);
    $historico->total();
    $total = (int)mysqli_fetch_array($historico->getTbl())[0];
    $totalPaginas = max(1, (int)ceil($total / $porPagina));
    if ($total > 0) {
        $historico->listar();
    }

    $titulo = $ehLivro ? $reg['livro'] : $reg['revista']." nº ".$reg['numero'];

    abrirPagina('Empréstimos', $menu);
    boasVindas();
?>
        <div class="grade-2">
            <!-- Resumo do livro/revista -->
            <section class="painel">
                <div class="painel-titulo">
                    <h2>Empréstimos d<?=$genero?> <?=$nome?></h2>
                    <a class="aba" href="<?=h($voltar)?>">Voltar</a>
                </div>
                <div class="ficha">
                    <?php if ($reg['capa'] != null) { ?>
                    <div class="capa-grande"><img src="capas/<?=h($reg['capa'])?>" alt="Capa de <?=h($titulo)?>"></div>
                    <?php } else { ?>
                    <div class="capa-grande sem-capa cor-<?=$codigo % 6?>"><?=h($titulo)?></div>
                    <?php } ?>
                    <dl class="dados">
                        <div class="inteiro">
                            <dt><?=$nome?></dt>
                            <dd><?=h($titulo)?></dd>
                        </div>
                        <div class="inteiro">
                            <dt><?=$ehLivro ? 'Autor' : 'Título'?></dt>
                            <dd><?=h($ehLivro ? $reg['autor'] : $reg['titulo'])?></dd>
                        </div>
                        <div class="inteiro">
                            <dt>Total de empréstimos</dt>
                            <dd><?=$total?></dd>
                        </div>
                    </dl>
                </div>
            </section>

            <!-- Formulário -->
            <section class="painel">
                <h2><?=$selecionado ? 'Alterar Empréstimo' : 'Novo Empréstimo'?></h2>
                <?php if ($mensagem !== "") { ?>
                <p class="mensagem<?=$erro ? ' erro' : ''?>" style="margin-bottom:18px"><?=h($mensagem)?></p>
                <?php } ?>
                <form action="<?=h($self.($selecionado ? '&idNome='.$idNome : ''))?>" method="post">
                    <div class="campos">
                        <div class="campo">
                            <label for="idSair" class="obrig">Data da Saída</label>
                            <input type="date" name="sair" id="idSair" value="<?=h($form['sair'])?>" required>
                        </div>
                        <div class="campo">
                            <label for="idEntrar">Data da Devolução</label>
                            <input type="date" name="entrar" id="idEntrar" value="<?=h($form['entrar'])?>">
                        </div>
                        <div class="campo inteiro">
                            <label for="idNomeEmprest" class="obrig">Emprestado para</label>
                            <input type="text" name="nome" id="idNomeEmprest" maxlength="50" value="<?=h($form['nome'])?>" required>
                        </div>
                    </div>
                    <div class="botoes">
                        <?php if ($selecionado) { ?>
                        <button type="submit" name="emprestar" value="Alterar" class="botao botao-primario">Salvar alteração</button>
                        <a href="<?=h($self)?>" class="botao botao-secundario">Novo empréstimo</a>
                        <?php } else { ?>
                        <button type="submit" name="emprestar" value="Adicionar" class="botao botao-primario">Adicionar</button>
                        <button type="reset" class="botao botao-secundario">Limpar</button>
                        <?php } ?>
                    </div>
                </form>
            </section>
        </div>

        <!-- Histórico -->
        <section class="painel">
            <h2>Histórico de Empréstimos</h2>
            <?php if ($total > 0) { ?>
            <p class="subtitulo" style="margin:-10px 0 14px">Clique em um nome para alterar o empréstimo.</p>
            <div class="tabela-rolagem">
                <table class="tabela">
                    <thead>
                        <tr><th>Nome</th><th>Saída</th><th>Devolução</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($linha = mysqli_fetch_assoc($historico->getTbl())) { ?>
                        <tr<?=($idNome && (int)$linha['id_emprest'] === $idNome) ? ' class="selecionado"' : ''?>>
                            <td><a href="<?=h($self.'&pagina='.$pagina.'&idNome='.(int)$linha['id_emprest'])?>"><?=h($linha['nome'])?></a></td>
                            <td><?=$linha['dt_emprest'] != null ? mesBR($linha['dt_emprest'])[1] : ''?></td>
                            <td><?=$linha['dt_devol'] != null ? mesBR($linha['dt_devol'])[1] : '<span class="selo emprestado">Em aberto</span>'?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php paginacao($pagina, $totalPaginas, $self.'&pagina='); ?>
            <?php } else { ?>
            <p class="subtitulo">Nenhum empréstimo registrado ainda.</p>
            <?php } ?>
        </section>
<?php
    fecharPagina();
?>
