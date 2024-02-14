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
            $this->setUsuario(addslashes(trim($user)));
            $this->setPrimNome(addslashes(trim($pNome)));
            $this->setEmail(addslashes(trim($email)));
            $this->setSenha(addslashes(trim($pswor)));
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
        
        public function validacao() {

        }

        public function cadastro() {
            $sql = "SELECT usuario FROM usuarios WHERE usuario = '".$this->getUsuario()."'";
            $conect = new Conexao($sql);
            $conect->conectar();

            if (mysqli_num_rows($conect->getResult()) === 0) {
                $sql = "INSERT INTO usuarios (usuario, nome, senha, email) VALUES ('".$this->getUsuario()."','".$this->getPrimNome()."','".password_hash($this->getSenha(),PASSWORD_DEFAULT)."','".$this->getEmail()."')";
                $conect = new Conexao($sql);
                $conect->conectar($sql);
                $mensagem = "Usuário cadastrado com sucesso";
            }
            else {
                $mensagem = "Usuário e Email já cadastrados";
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
            $sql = "SELECT * FROM usuarios WHERE usuario = '".$this->getUsuario()."'";
            $conect = new Conexao($sql);
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
                        header('location:principal.php');
                        session_start();
                        $_SESSION['user'] = $this->getUsuario();
                        $_SESSION['id_user'] = $tblValida['id_usuarios'];
                        $_SESSION['dataIn'] = date('d-m-Y');
                        $_SESSION['horaIn'] = date('H:i:s');
                        $_SESSION['last_time'] = time();
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
            $data = date('Y-m-d');
            $hora = date ('H:i:s');
            $sql = "INSERT INTO logins (dataIn, horaIn, dataOut, horaOut, id_usuarios) VALUES ('".$dt_in."', '".$_SESSION['horaIn']."', '$data', '$hora', '".$this->getId_user()."')";
            $conect = new Conexao($sql);
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
            $sql = "SELECT * FROM acessos WHERE id_usuarios = $id";
            $conect = new Conexao($sql);
            $conect->conectar();
            $this->setListaDados($conect->getResult());
        }
        
        public function tempo($id) {
            $sql = "SELECT * FROM acessos WHERE id_usuarios = $id";
            $conect = new Conexao($sql);
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

            $this->setTempoTotal($tempoD + intdiv($tempoH,24)."dias ".($tempoH%24 + intdiv($tempoM,60))."h ".($tempoM%60)."min");
        }
    }
?>