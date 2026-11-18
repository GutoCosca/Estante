<?php
    require_once ('php/funcao.php');
    exigirLogin();

    // Página restrita ao administrador (usuarios.admin = 1)
    if (empty($_SESSION['admin'])) {
        header('location:inicio.php');
        exit;
    }

    $listaUser = new Analise();
    $listaUser->usuarios();

    $busca = isset($_GET['busca']) && $_GET['busca'] !== '' ? (int)$_GET['busca'] : null;
    $acessos = array();
    $usuarioBuscado = null;
    $tempoTotal = "";
    if ($busca !== null) {
        $listaDados = new Analise();
        $listaDados->dados($busca);
        while ($dados = mysqli_fetch_array($listaDados->getListaDados())) {
            $usuarioBuscado = $dados[2];
            $entra = new DateTime($dados[3]." ".$dados[4]);
            $sai = new DateTime($dados[5]." ".$dados[6]);
            $duracao = $entra->diff($sai);
            $acessos[] = array(
                'dataIn' => date('d/m/Y', strtotime($dados[3])),
                'horaIn' => date('H:i:s', strtotime($dados[4])),
                'dataOut' => date('d/m/Y', strtotime($dados[5])),
                'horaOut' => date('H:i:s', strtotime($dados[6])),
                'tempo' => ($duracao->h + $duracao->days * 24)."h ".$duracao->i."min"
            );
        }
        $listaTempo = new Analise();
        $listaTempo->tempo($busca);
        $tempoTotal = $listaTempo->getTempoTotal();
    }

    abrirPagina('Análise', 'analise');
    boasVindas('Análise de Acessos');
?>
        <div class="grade-2">
            <!-- Usuários -->
            <section class="painel">
                <h2>Usuários</h2>
                <div class="tabela-rolagem">
                    <table class="tabela">
                        <thead>
                            <tr><th>ID</th><th>Usuário</th><th>Nome</th><th>Email</th></tr>
                        </thead>
                        <tbody>
                        <?php while ($u = mysqli_fetch_array($listaUser->getListaUser())) { ?>
                            <tr<?=((int)$u[0] === $busca) ? ' class="selecionado"' : ''?>>
                                <td><?=(int)$u[0]?></td>
                                <td><a href="analise.php?acao=detalhe&busca=<?=(int)$u[0]?>"><?=h($u[1])?></a></td>
                                <td><?=h($u[2])?></td>
                                <td><?=h($u[3])?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Acessos -->
            <section class="painel">
                <h2>Acessos do Usuário</h2>
                <form action="analise.php" method="get" style="margin-bottom:20px">
                    <input type="hidden" name="acao" value="detalhe">
                    <div class="campos" style="grid-template-columns:1fr auto;align-items:end">
                        <div class="campo">
                            <label for="idBusca">ID do usuário</label>
                            <input type="number" name="busca" id="idBusca" min="1" value="<?=$busca !== null ? $busca : ''?>">
                        </div>
                        <button type="submit" class="botao botao-primario">Buscar</button>
                    </div>
                </form>

                <?php if ($busca === null) { ?>
                <p class="subtitulo">Escolha um usuário na lista ou digite o ID.</p>
                <?php } elseif (count($acessos) === 0) { ?>
                <p class="subtitulo">Nenhum acesso registrado para este usuário.</p>
                <?php } else { ?>
                <p class="subtitulo" style="margin:-6px 0 12px"><strong><?=h($usuarioBuscado)?></strong> · <?=count($acessos)?> acesso<?=count($acessos) === 1 ? '' : 's'?></p>
                <div class="tabela-rolagem">
                    <table class="tabela">
                        <thead>
                            <tr><th>Entrada</th><th>Saída</th><th>Tempo</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($acessos as $a) { ?>
                            <tr>
                                <td><?=$a['dataIn']?> <?=$a['horaIn']?></td>
                                <td><?=$a['dataOut']?> <?=$a['horaOut']?></td>
                                <td><?=$a['tempo']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="2">Tempo total</td><td><?=h($tempoTotal)?></td></tr>
                        </tfoot>
                    </table>
                </div>
                <?php } ?>
            </section>
        </div>
<?php
    fecharPagina();
?>
