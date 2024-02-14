<?php
        // Fazer a conexção com o BD

use Conexao as GlobalConexao;

    class Conexao {
        private $servidor;
        private $usuario;
        private $senha;
        private $db;
        private $sql;
        private $result;

        public function __construct($sql) {
            $this->setServidor("localhost");
            $this->setUsuario("root");
            $this->setSenha("");
            $this->setDb("biblioteca");
            $this->setSql($sql);
        }

        public function getServidor() {
            return $this->servidor;
        }

        public function setServidor($serv) {
            $this->servidor = $serv;
        }

        public function getUsuario() {
            return $this->usuario;
        }

        public function setUsuario($usu) {
            $this->usuario = $usu;
        }

        public function getSenha() {
            return $this->senha;
        }

        public function setSenha($sw) {
            $this->senha = $sw;
        }

        public function getDb() {
            return $this->db;
        }

        public function setDb($db) {
            $this->db = $db;
        }

        public function getSql() {
            return $this->sql;
        }

        public function setSql($sql) {
                $this->sql = $sql;
        }

        public function getResult() {
            return $this->result;
        }

        public function setResult($result) {
                $this->result = $result;
        }

        public function conectar(){
            $conect = mysqli_connect($this->getServidor(), $this->getUsuario(), $this->getSenha(), $this->getDb());
            $this->setResult (mysqli_query($conect, $this->getSql()));
            
            if (!$this->getResult()) {
                exit("Não foi possível localizar.<br> Tente novamente");
            }            
        }
    }
?>