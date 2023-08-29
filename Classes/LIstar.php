<?php
    require_once ('Conexao.php');

    //lista e busca os arquivos do BD
    class Registros {
        private $sql;
        private $tabela;
        private $codigo;
        private $tbl;
        private $tipo;
        private $letra;
        private $ordem;

        public function __construct($tb, $tp, $lt, $od) {
            $this->setTabela($tb);
            if ($tp != null) {
                $this->setTipo($tp);
            }
            else{
                $this->setTipo("livro");
            }

            if ($lt != null) {
                $this->setLetra($lt);
            }

            if ($od != null) {
                $this->setOrdem($od);
            }

            else{
                $this->setOrdem("ASC");
            }
        }
        
        public function getTabela() {
                return $this->tabela;
        }

        public function setTabela($tb) {
                $this->tabela = $tb;
        }
        
        public function getCodigo() {
                return $this->codigo;
        }

        public function setCodigo($cod) {
                $this->codigo = $cod;
        }

        public function getSql() {
            return $this->sql;
        }
    
        public function setSql($sql) {
                $this->sql = $sql;
        }

        public function getTbl() {
            return $this->tbl;
        }

        public function setTbl($tbl) {
            $this->tbl = $tbl;
        }

        public function getTipo() {
            return $this->tipo;
        }

        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        public function getLetra() {
            return $this->letra;
        }

        public function setLetra($letra) {
            $this->letra = $letra;
        }

        public function getOrdem() {
            return $this->ordem;
        }

        public function setOrdem($ordem) {
            $this->ordem = $ordem;
        }

        public function listar() {
            if ($this->getLetra() != null) {
                $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE ".$this->getTipo()." LIKE '".$this->getLetra()."%' ORDER BY ".$this->getTipo()." ".$this->getOrdem().",  ano ".$this->getOrdem());
            }
            else {
                $this->setSql("SELECT * FROM ".$this->getTabela()." ORDER BY ".$this->getTipo()." ".$this->getOrdem().", ano ".$this->getOrdem());
            }
            
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function buscar($busCod) {
            $this->setCodigo($busCod);
            $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_livro = ".$this->getCodigo());
            
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

    }
?>