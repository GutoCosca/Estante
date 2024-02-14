<?php
    require_once ('conexao.php');
    function mesBR($compra){
        $numDia = date('d',strtotime($compra));
        $numMes = date('m',strtotime($compra))-1;
        $numAno = date('Y',strtotime ($compra));
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $compraBR = [$numDia."<br>".$mes[$numMes]."<br>".$numAno, $numDia." de ".$mes[$numMes]." de ".$numAno];
        return  $compraBR;
    }

    /*function registro($idusuario) {
        date_default_timezone_set('America/Sao_Paulo');
        $data = date('d/m/Y');
        $hora = date('H:i:s');
        $sql = "INSERT INTO logins(dia, hora, estado, id_usuarios) VALUES ('$data', '$hora', 'in', '$idusuario')";
        $conect = new Conexao($sql);
        $conect->conectar();
        return $sql;
    }
    */

    
