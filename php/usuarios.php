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
                        $_SESSION['dataIn'] = date('d/m/Y');
                        $_SESSION['horaIn'] = date('H:i:s');
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
            $data = date('d/m/Y');
            $hora = date ('H:i:s');
            $sql = "INSERT INTO logins (diaIn, horaIn, diaOut, horaOut, id_usuarios) VALUES ('".$_SESSION['dataIn']."', '".$_SESSION['horaIn']."', '$data', '$hora', '".$this->getId_user()."')";
            $conect = new Conexao($sql);
            $conect->conectar();
        }
    }
?>