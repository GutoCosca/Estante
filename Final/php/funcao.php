<?php
    require_once ('conexao.php');
    require_once ('registros.php');

    function mesBR($compra){
        $numDia = date('d',strtotime($compra));
        $numMes = date('m',strtotime($compra))-1;
        $numAno = date('Y',strtotime ($compra));
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $compraBR = [$numDia."<br>".$mes[$numMes]."<br>".$numAno, $numDia." de ".$mes[$numMes]." de ".$numAno];
        return  $compraBR;
    }

    function siteErro () {
        header('location:login.php');
    }

    function sessao($user) {
        if ($user != null) {
            $resp = "Bem vindo ".$user;
        }

        else {
            header('locacion:login.html');
        }
                
        return $resp;
    }

    function listar() {

    }