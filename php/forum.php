<?php
    class Forum {
        private $id_pergunta;
        private $id_periodico;
        private $id_usuarios;
        private $id_livros;
        private $id_revistas;
        private $topico;
        private $detalhe;
        private $dt_aberta;
        private $h_aberta;
        private $dt_fecha;
        private $h_fecha;
        private $fechado;
        private $limite;
        private $sql;
        private $tbl;
        private $periodico;

        public function __construct($user, $idPeri, $peri, $top, $det, $fechar, $lmt) {
            $this->setId_usuarios($user);
            $this->setId_periodico($idPeri);
            $this->setTopico(addslashes($top));
            $this->setDetalhe(addslashes($det));
            $this->setPeriodico($peri);
            $this->setFechado($fechar);
            $this->setLimite($lmt);
        }
        
        public function getId_pergunta() {
            return $this->id_pergunta;
        }
        
        public function setId_pergunta($id_pergunta) {
            $this->id_pergunta = $id_pergunta;
        }
        
        public function getId_usuarios() {
            return $this->id_usuarios;
        }
        
        public function setId_usuarios($user) {
            $this->id_usuarios = $user;
        }

        public function getId_periodico() {
            return $this->id_periodico;
        }
        
        public function setId_periodico($idPeri) {
            $this->id_periodico = $idPeri;
        }
        
        public function getId_livros() {
            return $this->id_livros;
        }
        
        public function setId_livros($li) {
            $this->id_livros = $li;
        }
        
        public function getId_revistas() {
            return $this->id_revistas;
        }
        
        public function setId_revistas($rev) {
            $this->id_revistas = $rev;
        }
        
        public function getTopico() {
            return $this->topico;
        }
        
        public function setTopico($top) {
            $this->topico = $top;
        }
        
        public function getDetalhe() {
            return $this->detalhe;
        }
        
        public function setDetalhe($det) {
            $this->detalhe = $det;
        }
        
        public function getDt_aberta() {
            return $this->dt_aberta;
        }
        
        public function setDt_aberta($dtabr) {
            $this->dt_aberta = $dtabr;
        }
        
        public function getH_aberta() {
            return $this->h_aberta;
        }
        
        public function setH_aberta($habr) {
            $this->h_aberta = $habr;
        }
        
        public function getDt_fecha() {
            return $this->dt_fecha;
        }
        
        public function setDt_fecha($dtfec) {
            $this->dt_fecha = $dtfec;
        }
        
        public function getH_fecha() {
            return $this->h_fecha;
        }
        
        public function setH_fecha($hfec) {
            $this->h_fecha = $hfec;
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

        public function getPeriodico() {
            return $this->periodico;
        }

        public function setPeriodico($perio) {
                $this->periodico = $perio;
        }

        public function getFechado() {
            return $this->fechado;
        }

        public function setFechado($fechar) {
            $this->fechado = $fechar;
        }

        public function getLimite() {
            return $this->limite;
        }

        public function setLimite($lmt) {
            $this->limite = $lmt;
        }

        public function instrucao() {
            if ($this->getPeriodico() == "editarLivro") {
                $this->setId_livros($this->getPeriodico());
                $column = "id_livros";
            }
            elseif ($this->getPeriodico() == "editarRevista") {
                $this->setId_revistas($this->getPeriodico());
                $column = "id_revistas";
            }
            else {
                $column="";
            }

            if ($this->getId_usuarios() != null) {
                $condic = "WHERE id_usuarios = ".$this->getId_usuarios();
            }
            else {
                $condic = "";
            }

            if ($this->getLimite() >= 0) {
                $limit = " LIMIT ".$this->getLimite().",8";
            }
            else {
                $limit = "";
            }
            $resposta = array($column, $condic, $limit);
            return $resposta;
        }
        
        public function abrir() {
            $instru = $this->instrucao();
            $this->setDt_aberta(date('Y-m-d'));
            $this->setH_aberta(date('h:m:s'));
            $this->setSql(
                "INSERT INTO forum_pergunta (id_usuarios, ".$instru[0].", topico, detalhe, dt_aberta, hr_aberta) VALUES (".$this->getId_usuarios().", ".$this->getId_periodico().", '".$this->getTopico()."', '".$this->getDetalhe()."', '".$this->getDt_aberta()."', '".$this->getH_aberta()."')"
            );
            $conect = new Conexao(($this->getSql()));
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function total() {
            $this->setSql(
                "SELECT COUNT(*) FROM forum_pergunta WHERE id_usuarios ".$this->getId_usuarios()." AND fechado = ".$this->getFechado()
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function listar() {
            $instru = $this->instrucao();
            $this->setSql(
                "SELECT * FROM forum_pergunta WHERE id_usuarios ".$this->getId_usuarios() ." AND fechado = ".$this->getFechado()." ORDER BY dt_aberta DESC".$instru[2]
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
        
        public function busca ($perg) {
            $this->setId_pergunta($perg);
            $this->setSql(
                "SELECT forum_pergunta.*, usuarios.nome FROM forum_pergunta JOIN usuarios ON forum_pergunta.id_usuarios = usuarios.id_usuarios WHERE forum_pergunta.id_pergunta = ".$this->getId_pergunta()
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
    }
?>