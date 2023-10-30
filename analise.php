<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/analise.css">
    <title>Analise de Log dos Usuários </title>
    <?php require_once ('php/usuarios.php'); ?>
</head>
<body>
    <main>
        <section class="seccaoAnalise" id="controle">
            <form action="" method="post">
                <label for="iduser">Id Usuário</label>
                <input type="text" name="iduser" id="">
                <input type="submit" value="buscar">
            </form>
        </section>
        <section class="seccaoAnalise" id="usuarios">
            <table>
                <caption> Lista dos Usuários</caption>
                <thead>
                    <tr>
                        <th class="analise">ID</th>
                        <th class="analise">Usename</th>
                        <th class="analise">Nome</th>
                        <th class="analise">email</th>
                    </tr>                    
                </thead>
                <?php
                    $listaUser = new Analise();
                    $listaUser->usuarios();
                    while ($usuarios = mysqli_fetch_array($listaUser->getListaUser())) {
                        $id = $usuarios[0];
                        $username = $usuarios[1];
                        $nome = $usuarios[2];
                        $email = $usuarios[3];
                   ?>
                        <tr>
                            <td class="analise"><?=$id?></td>
                            <td class="analise"><a href="analise.php?acao=detalhe?&busca=<?=$id?>"><?=$username?></a></td>
                            <td class="analise"><?=$nome?></td>
                            <td class="analise"><?=$email?></td>
                        </tr>
                   <?php     
                    }
                ?>
            </table>
        </section>
        <section class="seccaoAnalise" id="resultado">
            <?php
                if (isset($_REQUEST['acao']) == 'detalhe') {
                    $listaDados = new Analise();
                    $listaDados->tempo($_REQUEST['busca']);
                    $numRows = mysqli_num_rows($listaDados->getListaDados());
                    ?>
                <table id="dados">
                    <caption>Analise dos Log do Usuário</caption>
                    <thead>
                        <tr>
                            <th rowspan="2" class="analise">ID</th>
                            <th rowspan="2" class="analise">Usuário</th>
                            <th colspan="2" class="analise">ENTRADA</th>
                            <th colspan="2" class="analise">SAÍDA</th>
                            <th rowspan="2" class="analise">Tempo</th>
                        </tr>
                        <tr>
                            <th class="analise">Data</th>
                            <th class="analise">Hora</th>
                            <th class="analise">Data</th>
                            <th class="analise">Hora</th>
                        </tr>
                    </thead>
                    <?php
                        $horaTempD = 0;
                        $horaTempH = 0;
                        $horaTempM = 0;
                        while ($dados = mysqli_fetch_array($listaDados->getListaDados())) {
                            $id = $dados[0];
                            $idUser = $dados[1];
                            $user = $dados[2];
                            $dataIn = date('d-m-Y', strtotime($dados[3]));
                            $horaIn = date('H:i:s', strtotime($dados[4]));
                            $dataOut = date('d-m-Y', strtotime($dados[5]));
                            $horaOut = date('H:i:s', strtotime($dados[6]));
                            $online = $dataIn." ".$horaIn;
                            $offline =$dataOut." ".$horaOut;
                            $entra = new DateTime($online);
                            $sai = new DateTime($offline);
                            $total = $entra->diff($sai);
                            $horaTot = $total->h + ($total->days * 24)."h ".$total->i."min";
                            $horaTotTemp = $total->days.":".$total->h.":".$total->i;
                            $horaTemp = explode(":",$horaTotTemp);
                            $horaTempD = $horaTempD + $horaTemp[0];
                            $horaTempH = $horaTempH + $horaTemp[1];
                            $horaTempM = $horaTempM + $horaTemp[2];
                    ?>
                            <tr class="analisar">
                                <td class="analise"><?=$idUser?></td>
                                <td class="analise"><?=$user?></td>
                                <td class="analise"><?=$dataIn?></td>
                                <td class="analise"><?=$horaIn?></td>
                                <td class="analise"><?=$dataOut?></td>
                                <td class="analise"><?=$horaOut?></td>
                                <td class="analise"><?=$horaTot?></td>
                                <?php
                        }
                        $tempHora = $horaTempD + ($horaTempH/24)."dias ".($horaTempH%24 + intdiv($horaTempM,60))."h ".($horaTempM%60)."min";
                        ?>
                    </tr>
                    <tr>
                    <td class="analise" colspan="5">Tempo Total</td>
                    <td class="analise" colspan="2"><?=$tempHora?></td>
                </tr>
                <?php
                    }
                ?>
            </table>
        </section>
    </main>
</body>
</html>