<?php
    require_once ('conexao.php');
    require_once ('registros.php');
    require_once ('atividade.php');
    require_once ('layout.php');

    // Escapa texto para exibir com segurança no HTML (evita XSS)
    function h($texto) {
        return htmlspecialchars((string)($texto ?? ''), ENT_QUOTES, 'UTF-8');
    }

    function mesBR($compra){
        $numDia = date('d',strtotime($compra));
        $numMes = date('m',strtotime($compra))-1;
        $numAno = date('Y',strtotime ($compra));
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $compraBR = [$numDia."<br>".$mes[$numMes]."<br>".$numAno, $numDia." de ".$mes[$numMes]." de ".$numAno, $numDia." / ".$mes[$numMes]." / ".$numAno];
        return  $compraBR;
    }

    function semanaBR($sema) {
        $dias = array(
            'Monday' => 'Segunda-feira',
            'Tuesday' => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday' => 'Quinta-feira',
            'Friday' => 'Sexta-feira',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        );
        return $dias[$sema] ?? '';
    }

    function siteErro () {
        header('location:login.php');
        exit;
    }

    function sessao($user) {
        if ($user == null) {
            header('location:login.php');
            exit;
        }
        return "Bem vindo ".h($user);
    }

    function logout () {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Registra o acesso (entrada/saída) na tabela logins
        if (isset($_SESSION['id_user'], $_SESSION['dataIn'], $_SESSION['horaIn'])) {
            $monitor = new Monitor($_SESSION['id_user']);
            $monitor->acesso();
        }
        session_unset();
        session_destroy();
        header('location:index.html');
        exit;
    }

    // Início padrão das páginas internas: trata o "Sair", exige login e
    // encerra a sessão após 30 minutos sem atividade.
    function exigirLogin() {
        if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'logout') {
            logout();
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id_user'])) {
            logout();
        }
        $ativo = new Atividade();
        $ativo->tempo();
    }
