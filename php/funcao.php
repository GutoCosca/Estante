<?php
    function mesBR($compra){
        $numDia = date('d',strtotime($compra));
        $numMes = date('m',strtotime($compra))-1;
        $numAno = date('Y',strtotime ($compra));
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $compraBR = $numDia."<br>".$mes[$numMes]."<br>".$numAno;
        return  $compraBR;
    }
    