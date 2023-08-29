<?php
        require_once ('Conexao.php');
    class Logar {
        private $usuario;
        private $senha;
        private $tabela;
        private $msg;

        
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

        public function getTabela() {
                return $this->tabela;
        }

        public function setTabela($tabela) {
                $this->tabela = $tabela;
        }

        public function getMsg() {
                return $this->msg;
        }

        public function setMsg($msg) {
                $this->msg = $msg;
        }
    }
?>