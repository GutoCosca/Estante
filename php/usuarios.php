<?php 
    include_once ('conexao.php');
    date_default_timezone_set('America/Sao_Paulo');
    

    // FAZ O CADASTRO E O LOGIN DO USUÁRIO
    
    class Cadastrar {
        private $usuario;
        private $primNome;
        private $email;
        private $senha;

        public function __construct($user, $pNome, $email, $pswor) {
            $this->setUsuario(trim((string)$user));
            $this->setPrimNome(trim((string)$pNome));
            $this->setEmail(trim((string)$email));
            $this->setSenha(trim((string)$pswor));
        }
 
        public function getUsuario() {
            return $this->usuario;
        }

        public function setUsuario($user) {
            $this->usuario = $user;
        }

        public function getPrimNome() {
            return $this->primNome;
        }

        public function setPrimNome($pNome) {
            $this->primNome = $pNome;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function getSenha() {
            return $this->senha;
        }

        public function setSenha($senha) {
                $this->senha = $senha;
        }
        
        // Validação no servidor (o JavaScript pode ser burlado)
        public function validacao() {
            $tamUser = mb_strlen(($this->getUsuario()));
            $tamNome = mb_strlen(($this->getPrimNome()));
            $senha = $this->getSenha();

            if ($tamUser < 3 || $tamUser > 12 || $tamNome < 3 || $tamNome > 12) {
                return "Usuário e nome devem ter de 03 a 12 caracteres";
            }
            if (!filter_var(($this->getEmail()), FILTER_VALIDATE_EMAIL)) {
                return "Email inválido";
            }
            if (strlen($senha) < 8 || strlen($senha) > 12 || !preg_match('/[A-Z]/', $senha) || !preg_match('/[a-z]/', $senha) || !preg_match('/[0-9]/', $senha) || !preg_match('/[\W_]/', $senha)) {
                return "A senha não atende às regras";
            }
            return "";
        }

        public function cadastro() {
            $erro = $this->validacao();
            if ($erro != "") {
                return $erro;
            }

            $conect = new Conexao("SELECT usuario FROM usuarios WHERE usuario = ? OR email = ?", array($this->getUsuario(), $this->getEmail()));
            $conect->conectar();

            if (mysqli_num_rows($conect->getResult()) === 0) {
                $conect = new Conexao(
                    "INSERT INTO usuarios (usuario, nome, senha, email) VALUES (?, ?, ?, ?)",
                    array($this->getUsuario(), $this->getPrimNome(), password_hash($this->getSenha(), PASSWORD_DEFAULT), $this->getEmail())
                );
                $conect->conectar();
                $mensagem = "Usuário cadastrado com sucesso";
            }
            else {
                $mensagem = "Usuário ou email já cadastrado";
            }

            return $mensagem;
        }
    }

        //Faz o login e logout do usuário
    class Logar {
        private $usuario;
        private $senha;

        public function __construct($user, $pswor) {
            $this->setUsuario(trim($user??""));
            $this->setSenha($pswor??"");
        }
        public function getUsuario() {
                return $this->usuario;
        }

        public function setUsuario($usuario) {
                $this->usuario = $usuario;
        }

        public function getSenha() {
                return $this->senha;
        }

        public function setSenha($senha) {
                $this->senha = $senha;
        }

        public function validar() {
            $mensagem = "";
            $conect = new Conexao("SELECT * FROM usuarios WHERE usuario = ?", array($this->getUsuario()));
            $conect->conectar();
            $tblValida = mysqli_fetch_assoc($conect->getResult());
            
            if (!$tblValida) {
                $mensagem = "Usuário e senha inválidas";
            }
            else{
                $userResult = $tblValida['usuario'];
                $senhaResult = $tblValida['senha'];

                if ($userResult === $this->getUsuario()) {
                    if (password_verify($this->getSenha(), $senhaResult)) {
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        session_regenerate_id(true);
                        $_SESSION['user'] = $this->getUsuario();
                        $_SESSION['nome'] = $tblValida['nome']; // usado no "Bem-vindo, ..." 
                        $_SESSION['id_user'] = $tblValida['id_usuarios'];
                        $_SESSION['admin'] = (int)($tblValida['admin'] ?? 0); // 1 = pode ver analise.php
                        $_SESSION['dataIn'] = date('d-m-Y');
                        $_SESSION['horaIn'] = date('H:i:s');
                        $_SESSION['last_time'] = time();
                        header('location:inicio.php');
                        exit;
                    }
                    else {
                        $mensagem = "Usuário e senha inválidas";
                    }
                }
                else {
                    $mensagem = "Usuário e senha inválidas";
                }
            }
            return $mensagem;
        }
    }

    class Monitor{
        private $idusuario;

        public function __construct($iduser) {
            $this->setId_user($iduser);
        }

        public function getId_user() {
                return $this->idusuario;
        }

        public function setId_user($iduser) {
                $this->idusuario = $iduser;
        }
        
        public function acesso() {
            $dt_in = date('Y-m-d', strtotime($_SESSION['dataIn']));
            $conect = new Conexao(
                "INSERT INTO logins (dataIn, horaIn, dataOut, horaOut, id_usuarios) VALUES (?, ?, ?, ?, ?)",
                array($dt_in, $_SESSION['horaIn'], date('Y-m-d'), date('H:i:s'), (int)$this->getId_user())
            );
            $conect->conectar();
        }
    }

    class Analise {
        private $iduser;
        private $listaUser;
        private $listaDados;
        private $tempoTotal;

        public function getIduser() {
                return $this->iduser;
        }

        public function setIduser($iduser) {
                $this->iduser = $iduser;
        }
        
        public function getListaUser() {
            return $this->listaUser;
        }
        
        public function setListaUser($listaUser) {
            $this->listaUser = $listaUser;
        }

        public function getListaDados() {
            return $this->listaDados;
        }
        
        public function setListaDados($listaDados) {
            $this->listaDados = $listaDados;
        }
        
        public function getTempoTotal() {
                return $this->tempoTotal;
        }

        public function setTempoTotal($tempoTotal) {
                $this->tempoTotal = $tempoTotal;
        }

        public function usuarios(){
            $sql = "SELECT * FROM listauser";
            $conect = new Conexao($sql);
            $conect->conectar();
            $this->setListaUser($conect->getResult());
        }

        public function dados($id) {
            $conect = new Conexao("SELECT * FROM acessos WHERE id_usuarios = ? ORDER BY dataIn, horaIn", array((int)$id));
            $conect->conectar();
            $this->setListaDados($conect->getResult());
        }
        
        public function tempo($id) {
            $conect = new Conexao("SELECT * FROM acessos WHERE id_usuarios = ?", array((int)$id));
            $conect->conectar();
            $this->setListaDados($conect->getResult());
            $tempoD = 0;
            $tempoH = 0;
            $tempoM = 0;

            while ($dados = mysqli_fetch_array($this->getListaDados())) {
                $online = new DateTime($dados[3]." ".$dados[4]);
                $offline = new DateTime($dados[5]." ".$dados[6]);
                $subTotal = $online->diff($offline);
                $total = explode(":", $subTotal->days.":".$subTotal->h.":".$subTotal->i);
                $tempoD = $tempoD + $total[0];
                $tempoH = $tempoH + $total[1];
                $tempoM = $tempoM + $total[2];
            }

            // normaliza minutos -> horas -> dias antes de montar o texto
            $tempoH = $tempoH + intdiv($tempoM, 60);
            $tempoD = $tempoD + intdiv($tempoH, 24);
            $this->setTempoTotal($tempoD." dias ".($tempoH % 24)."h ".($tempoM % 60)."min");
        }
    }
?>