<?php
    require_once ('conexao.php');
    require_once ('registros.php');

    function mesBR($compra){
        $numDia = date('d',strtotime($compra));
        $numMes = date('m',strtotime($compra))-1;
        $numAno = date('Y',strtotime ($compra));
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $compraBR = [$numDia."<br>".$mes[$numMes]."<br>".$numAno, $numDia." de ".$mes[$numMes]." de ".$numAno, $numDia." / ".$mes[$numMes]." / ".$numAno];
        return  $compraBR;
    }

    function semanaBR($sema) {
        switch ($sema) {
            case 'Monday':
                $semaBR = 'Segunda-feira';
                break;

            case 'Tuesday':
                $semaBR = 'Terça-feira';
                break;

            case 'Wednesday':
                $semaBR = 'Quarta-feira';
                break;

            case 'Thursday':
                $semaBR = 'Quinta-feira';
                break;
            
            case 'Friday':
                $semaBR = 'Sexta-feira';
                break;

            case 'Saturday':
                $semaBR = 'Sábado';
                break;

            case 'Sunday':
                $semaBR = 'Domingo';
                break;
        }
        return $semaBR;
    }

    function siteErro () {
        header('location:login.php');
    }

    function sessao($user) {
        if ($user != null) {
            $resp = "Bem vindo ".$user;
        }

        else {
            header('locacion:login.php');
        }
                
        return $resp;
    }

    function logout () {
        session_unset();
        session_destroy();
        header('location:principal.html');
    }