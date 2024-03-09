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
?>
<body>
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
            <section id="idForumLista">
                <h3>Meus Foruns</h3>
                <table>
                    <thead>
                        <tr>
                            <th>TÓPICO</th>
                            <th>PERIÓDICO</th>
                            <th>ABERTURA</th>
                            <th>FECHAMENTO</th>
                        </tr>
                        
                    </thead>
                        <?php
                            $id_usuario = "= ".$_SESSION['id_user'];
                            $listaCria = new Forum($id_usuario, "", "","","",);
                            $listaCria->listar();
                            while ($tblListaCria = mysqli_fetch_array($listaCria->getTbl())) {
                                if ($tblListaCria['id_livros'] != null ) {
                                    $periodico = "livro";
                                    $listaPeriodico= new Registros (
                                        $tblListaCria['id_usuarios'],
                                        "livros",
                                        "",
                                        "","","","","",""
                                    );
                                    $listaPeriodico->buscar($tblListaCria['id_livros']);
                                    $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                                    $periodicoNome = $tblPeriodico['livro'];
                                }
                                elseif ($tblListaCria['id_revistas'] != null) {
                                    $periodico = "revista";
                                    $listaPeriodico= new Registros (
                                        $tblListaCria['id_usuarios'],
                                        "revistas",
                                        "",
                                        "","","","","",""
                                    );
                                    $listaPeriodico->buscar($tblListaCria['id_revistas']);
                                    $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                                    $periodicoNome = $tblPeriodico['titulo'];
                                }
                                else {
                                    $periodico = "";
                                    $periodicoNome = "";
                                }
                        ?>
                        <tr>
                            <td><a href="forum.php?tipo=<?=$periodico?>&topico=<?=$tblListaCria['0']?>"><?=$tblListaCria['topico']?></a></td>
                            <td><?=$periodicoNome?></td>
                            <td><?=$tblListaCria['dt_aberta']?></td>
                            <td><?=$tblListaCria[2]?></td>
                        </tr>
                        <?php
                            }
                            
                        ?>
                </table>
                <h3>Outros Foruns</h3>
                <table>
                    <thead>
                        <tr>
                            <th>TÓPICO</th>
                            <th>PERIÓDICO</th>
                            <th>ABERTURA</th>
                            <th>FECHAMENTO</th>
                        </tr>
                        
                    </thead>
                        <?php
                            $id_usuario = "<> ".$_SESSION['id_user'];
                            $listaCria = new Forum($id_usuario, "", "","","",);
                            $listaCria->listar();
                            while ($tblListaCria = mysqli_fetch_array($listaCria->getTbl())) {
                                if ($tblListaCria['id_livros'] != null ) {
                                    $periodico = "livro";
                                    $listaPeriodico= new Registros (
                                        $tblListaCria['id_usuarios'],
                                        "livros",
                                        "",
                                        "","","","","",""
                                    );
                                    $listaPeriodico->buscar($tblListaCria['id_livros']);
                                    $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                                    $periodicoNome = $tblPeriodico['livro'];
                                    print_r($listaPeriodico);
                                }
                                elseif ($tblListaCria['id_revistas'] != null) {
                                    $periodico = "revista";
                                    $listaPeriodico= new Registros (
                                        $tblListaCria['id_usuarios'],
                                        "revistas",
                                        "",
                                        "","","","","",""
                                    );
                                    $listaPeriodico->buscar($tblListaCria['id_revistas']);
                                    $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                                    $periodicoNome = $tblPeriodico['titulo'];
                                }
                                else {
                                    $periodico = "";
                                    $periodicoNome = "";
                                }
                        ?>
                        <tr>
                            <td><a href="forum.php?tipo=<?=$periodico?>&topico=<?=$tblListaCria['0']?>"><?=$tblListaCria['topico']?></a></td>
                            <td><?=$periodicoNome?></td>
                            <td><?=$tblListaCria['dt_aberta']?></td>
                            <td><?=$tblListaCria[2]?></td>
                        </tr>
                        <?php
                            }
                            
                        ?>
                </table>
            </section>
            <section id="idForumResposta">
                <?php
                    if(isset($_REQUEST['topico'])) {
                        $listaPerg = new Forum("","","","","");
                        $listaPerg->busca($_REQUEST['topico']);
                        $tblPerg = mysqli_fetch_array($listaPerg->getTbl());
                        $nome = $tblPerg['nome'];
                        $data = $tblPerg['dt_aberta'];
                        $hora = $tblPerg['hr_aberta'];
                        if ($tblPerg['id_livros'] != null) {
                            $periodico = "LIVRO:";
                            $listaPeriodico = new Registros("","livros","","","","","","","");
                            $listaPeriodico->buscar($tblPerg['id_livros']);
                            $tblPeriodico = mysqli_fetch_array($listaPeriodico->getTbl());
                            $titulo = "LIVRO: ".$tblPeriodico['livro'];
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
                    <h4>CRIADO POR: <?=$nome?></h4>
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
</body>
</html>