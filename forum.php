<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/forum.css">
    <title>Estante Virtual</title>
</head>
<?php
    require_once ('php/registros.php');
    require_once ('php/funcao.php');
    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'logout') {
        logout();
    }
    session_start();
    if (isset($_SESSION['id_user'])) {
        $logado = sessao($_SESSION['user']);
        $ativo = new Atividade();
        $ativo->tempo();
        $horario = semanaBR(date('l'))." - ".mesBR(date('Y-m-d'))[1];
    }
    else {
        logout();
    }
    if (isset($_GET['condicao'])) {
        $condi = "?condicao=".$_GET['condicao']."&";
        $condJS = $_GET['condicao'];
        if (isset($_GET['forum'])) {
            $foru = "&forum=".$_GET['forum'];
            $requisicao = "?condicao=".$_GET['condicao']."&forum=".$_GET['forum']."&";
            $forumJS = $_GET['forum'];
        }
        else {
            $foru = "";
            $requisicao = "?condicao=".$_GET['condicao']."&";
            $forumJS = "";
        }
    }
    else {
        $condi = "?";
        $condJS = "";
        if (isset($_GET['forum'])) {
            $foru = "&forum=".$_GET['forum'];
            $requisicao = "?forum=".$_GET['forum']."&";
            $forumJS = $_GET['forum'];
        }
        else {
            $foru = "";
            $requisicao = "?";
            $forumJS = "";
        }
    }

    if (isset($_GET['tipo'])) {
        $reqResp = "&tipo=".$_GET['tipo']."&topico=".$_GET['topico'];
    }    
    else {
        $reqResp = "";
    }    

    if (isset($_GET['pagForum']) && $_GET['pagForum'] != null) {
        $pagForum = $_GET['pagForum'];
        $reqPagForum = "&pagForum=".$_GET['pagForum'];
    }
    else {
        $pagForum = 1;
        $reqPagForum = "";
    }
    $limitForum = ($pagForum -1) *8;
    if (isset($_GET['pagResp']) && $_GET['pagResp'] != null) {
        $pagResp = $_GET['pagResp'];
    }
    else {
        $pagResp = 1;
    }
    $limitResp = ($pagResp -1) *5;
?>
<body onload="susup()">
    <main>
        <header>
            <h1>ESTANTE VIRTUAL</h1>
            <div id="idIdent">
                <p class="ident"><?=$logado?></p>
            </div>
        </header>
        <menu>
            <ul>
                <li><a href="inicio.php">Início</a></li>
                <li><a href="livros.php">Livros</a></li>
                <li><a href="revistas.php">Revistas</a></li>
                <li><a href="forum.php">Forum</a></li>
                <li><a href="?acao=logout">Sair</a></li>
            </ul>
            <p id="idData"><?=$horario?></p>
        </menu>
        <!-- Menu Tipo de Forum  -->
            <section id="idForumLista">
                <menu class="subMenu" id="idSubmenu01" onmouseover="compl01()" onmouseout="compl02()">
                    <ul>
                        <li><a href="<?=$condi?>forum=pessoal<?=$reqResp?>" onclick="botaoCondicao('#idBotao01', '#idBotao02')">Meus Forums</a></li>
                        <li><a href="<?=$condi?>forum=outros<?=$reqResp?>" onclick="botaoCondicao('#idBotao02', '#idBotao01')">Outros Foruns</a></li>
                    </ul>
                </menu>
                <menu class="subMenu" id="idSubmenu02">
                    <ul>
                        <li><a href="?condicao=abertos<?=$foru?><?=$reqResp?>" onclick="botaoCondicao('#idBotao03', '#idBotao04')">Abertos</a></li>
                        <a href="?condicao=fechados<?=$foru?><?=$reqResp?>" onclick="botaoCondicao('#idBotao04', '#idBotao03')">Fechados</a></li>
                    </ul>
                </menu>
                <!-- Lista o forum -->
                <?php
                    if (isset($_REQUEST['condicao']) && $_REQUEST['condicao'] == "fechados") {
                        $fechar = 1;
                        $tituloCompl = "Fechados";
                    }
                    else {
                        $fechar = 0;
                        $tituloCompl = "Abertos";
                    }

                    if(isset($_GET['forum']) && $_GET['forum'] == "outros") {
                        $id_usuario = "<> ".$_SESSION['id_user'];
                        $titulo = "Outros Foruns ".$tituloCompl;
                    }
                    else {
                        $id_usuario = "= ".$_SESSION['id_user'];
                        $titulo = "Meus Foruns ".$tituloCompl;
                    }
                    $listaPerg = new Forum (
                        $id_usuario,
                        "","","","",
                        $fechar,
                        $limitForum
                    );
                    $listaPerg->total();
                    $count = mysqli_fetch_array($listaPerg->getTbl());
                    
                ?>
                <div>
                    <h3 id="idTituloForum"><?=$titulo?></h3>
                    <?php
                    if ($count[0] > 0) {
                        $totPagForum = ceil($count[0]/8);
                        $listaPerg->listar();
                    ?>
                    <table class="tabelaForum">                    
                    <?php
                        while ($tblListaPerg = mysqli_fetch_array($listaPerg->getTbl())) {
                            $dtAbre = date('d-m-Y', strtotime($tblListaPerg['dt_aberta']));
                            $dtFecha = date('d-m-Y', strtotime($tblListaPerg['dt_fecha']));
                            if ($tblListaPerg['id_livros'] != null) {
                                $periodico = "livro";
                                $tabela = "livros";
                                $id_periodico = "id_livros";
                                $tabelaTH = "LIVRO:";
                            }
                            elseif ($tblListaPerg['id_revistas'] != null) {
                                $periodico = "revista";
                                $tabela = "revistas";
                                $id_periodico = "id_revistas";
                                $tabelaTH = "REVISTA:";
                            }

                            $listaPeriodico = new Registros (
                                $tblListaPerg['id_usuarios'],
                                $tabela,
                                "","","","","","",""
                            );
                            $listaPeriodico->buscar($tblListaPerg["$id_periodico"]);
                            $tblListaPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                            if ($periodico == "revista"){
                                $periodicoNome = $tblListaPeriodico['titulo']." - ".$tblListaPeriodico['revista']." nº ".$tblListaPeriodico['numero'];
                            }
                            else {
                                $periodicoNome = $tblListaPeriodico['livro'];
                            }

                            if ($tblListaPeriodico['capa'] != null) {
                                $capa = '<img src="capas/'.$tblListaPeriodico['capa'].'">';
                            }
                            else {
                                $capa = "";
                            }
                    ?>
                        <tr>
                            <td rowspan="4" style="border-bottom-style: double" id="idCelCapa"><?=$capa?></td>
                            <th><?=$tabelaTH?></th>
                            <th>ABERTURA</th>
                        </tr>
                        <tr>
                            <td><?=$periodicoNome?></td>
                    <?php
                            if ($fechar == 0) {
                    ?>
                            <td class="data" rowspan="3" style="border-bottom-style: double"><?=$dtAbre."<br>".$tblListaPerg['hr_aberta']?></td>
                        </tr>
                        <tr>
                            <th>TÓPICO:</th>
                        </tr>
                        <tr>
                            <td style="border-bottom-style: double"><a href="<?=$requisicao?>tipo=<?=$periodico?>&topico=<?=$tblListaPerg['id_pergunta']?><?=$reqPagForum?>"><?=$tblListaPerg['topico']?></a></td>
                        </tr>
                    <?php
                            }
                            elseif ($fechar == 1) { 
                    ?>
                            <td class="data"><?=$dtAbre."<br>".$tblListaPerg['hr_aberta']?></td>
                        </tr>
                        <tr>
                            <th>TÓPICO:</th>
                            <th>FECHAMENTO:</th>
                        </tr>
                        <tr>
                            <td style="border-bottom-style: double"><a href="<?=$requisicao?>tipo=<?=$periodico?>&topico=<?=$tblListaPerg['id_pergunta']?><?=$reqPagForum?>"><?=$tblListaPerg['topico']?></a></td>
                            <td class="data" style="border-bottom-style: double"><?=$dtFecha."<br>".$tblListaPerg['hr_fecha']?></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                    </table>
                    <!-- Seletor de Paginas -->
                    <?php
                        if ($count[0] > 8) {
                            $totPagiForum = $totPagForum;
                            if ($pagForum > 1) {
                                $antForum = $pagForum - 1;
                            }
                            else {
                                $antForum = $pagForum;
                            }

                            if ($pagForum < $totPagiForum) {
                                $proxForum = $pagForum + 1;
                            }
                            else {
                                $proxForum = $pagForum;
                            }

                            $iniForum = "pagForum=1";

                            if (isset($_GET['condicao']) || isset($_GET['forum']) || isset($_GET['tipo'])) {
                                $reqForumPag = $requisicao.$reqResp."&";
                            }
                            else {
                                $reqForumPag = "?";
                            }
                    ?>
                    <nav id="idSeletorPag">
                        <ul>
                            <li class="pagi"><a href="<?=$reqForumPag?><?=$iniForum?>" class="paginacao"><<</a></li>
                            <li class="pagi"><a href="<?=$reqForumPag?>pagForum=<?=$antForum?>" class="paginacao"><</a></li>
                            <li class="pagi" id="idPagina">Página <?=$pagForum?> / <?=$totPagForum?></li>
                            <li class="pagi"><a href="<?=$reqForumPag?>pagForum=<?=$proxForum?>" class="paginacao">></a></li>
                            <li class="pagi"><a href="<?=$reqForumPag?>pagForum=<?=$totPagiForum?>" class="paginacao">>></a></li>
                        </ul>
                    </nav>
                </div>
                    <?php
                        }
                    }
                    else {
                    ?>
                <h3>Não há Forums</h3>
                    <?php
                    }
                    ?>
            </section>
            <!-- Exibe o todo o forum selecionado -->
            <section id="idForumResposta">
                <?php
                    if(isset($_REQUEST['topico'])) {
                        $listaPerg = new Forum("","","","","","","");
                        $listaPerg->busca($_REQUEST['topico']);
                        $tblPerg = mysqli_fetch_array($listaPerg->getTbl());
                        $nome = $tblPerg['nome'];
                        $data = date('d-m-Y',strtotime($tblPerg['dt_aberta']));
                        $hora = $tblPerg['hr_aberta'];
                        if ($tblPerg['id_livros'] != null) {
                            $periodico = "LIVRO:";
                            $listaPeriodico = new Registros("","livros","","","","","","","");
                            $listaPeriodico->buscar($tblPerg['id_livros']);
                            $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                            $titulo = "LIVRO: ".$tblPeriodico['livro'];
                            $autor = "Autor: ".$tblPeriodico['autor'];
                            if($tblPeriodico['capa'] != null) {
                                $capa = $tblPeriodico['capa'];
                            }
                            else {
                                $capa = "";
                            }
                        }
                        elseif ($tblPerg['id_revistas'] != null) {
                            $periodico = "REVISTA:";
                            if ($tblPerg['id_revistas'] != null) {
                                $periodico = "REVISTA:";
                                $listaPeriodico = new Registros("","revistas","","","","","","","");
                                $listaPeriodico->buscar($tblPerg['id_revistas']);
                                $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                                $titulo = "REVISTA: ".$tblPeriodico['revista']." - ".$tblPeriodico['titulo']." N.º".$tblPeriodico['numero'];
                            }
                        }
                        else {
                            $periodico = "";
                            $titulo = "";
                            $nome = "";
                        }
                ?>
                <div>
                    <h3><?=$titulo?></h3>
                    <h3>TOPICO: <?=$tblPerg['topico']?></h3>
                    <p><?=$tblPerg['detalhe']?></p>
                    <h4>POR: <?=$nome?></h4>
                    <p>DATA / HORA: <?=$data?> ÀS <?=$hora?></p>
                </div>
                <?php
                    }
                ?>
            </section>
        <footer>
            <p>Desenvolvido por Gustavo Coscarello</p>
        </footer>
    </main>
    <script language="javascript">

        var menu = document.getElementsByClassName('subMenu');

        function susup() {
            document.querySelector('#idSubmenu01').style.marginTop = '0px';
            document.querySelector('#idSubmenu01').style.transition = 'margin-top 1s linear 1s';
        }

        function compl01() {
            document.querySelector('#idSubmenu02').style.marginTop = '0px';
            document.querySelector('#idSubmenu02').style.transition = 'margin-top 1s linear .5s';
        }

        function compl02() {
            document.querySelector('#idSubmenu02').style.marginTop = '-30px';
            document.querySelector('#idSubmenu02').style.transition = 'margin-top 1s linear 2s';
        }
    </script>
</body>
</html>