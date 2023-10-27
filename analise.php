<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/analise.css">
    <title>Analise de Log dos Usuários </title>
</head>
<body>
    <section class="seccaoAnalise" id="controle">
        <form action="" method="post">
            <label for="iduser">Id_User</label>
            <input type="text" name="iduser" id="">
        </form>
    </section>
    <section class="seccaoAnalise" id="resultado">
        <?php
            require_once ('php/usuarios.php');
            
            $resp = new Analise(1);
            $resp->tempo();
            ?>
        <table>
            <caption>Analise dos Log dos Usuários</caption>
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
                while ($teste=mysqli_fetch_array($resp->getLista())) {
                    $id = $teste[0];
                    $idUser = $teste[1];
                    $user = $teste[2];
                    $dataIn = date('d-m-Y', strtotime($teste[3]));
                    $horaIn = date('H:i:s', strtotime($teste[4]));
                    $dataOut = date('d-m-Y', strtotime($teste[5]));
                    $horaOut = date('H:i:s', strtotime($teste[6]));
                    $online = $dataIn." ".$horaIn;
                    $offline =$dataOut." ".$horaOut;
                    $entra = new DateTime($online);
                    $sai = new DateTime($offline);
                    $total = $entra->diff($sai);
                    $horaTot = $total->h + ($total->days * 24)."h ".$total->i."min";
            ?>
                    <tr class="analisar">
                        <td class="analise"><?=$user?></td>
                        <td class="analise"><?=$idUser?></td>
                        <td class="analise"><?=$dataIn?></td>
                        <td class="analise"><?=$horaIn?></td>
                        <td class="analise"><?=$dataOut?></td>
                        <td class="analise"><?=$horaOut?></td>
                        <td class="analise"><?=$horaTot?></td>
                    </tr>
                    <?php
                }
                ?>
        </table>
    </section>
</body>
</html>