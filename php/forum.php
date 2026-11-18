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
            $this->setId_periodico((int)$idPeri);
            $this->setTopico((string)$top);
            $this->setDetalhe((string)$det);
            $this->setPeriodico($peri);
            $this->setFechado((int)$fechar);
            $this->setLimite(($lmt === "" || $lmt === null) ? -1 : (int)$lmt);
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
                $column = "id_livros";
            }
            elseif ($this->getPeriodico() == "editarRevista") {
                $column = "id_revistas";
            }
            else {
                $column = "";
            }

            // id_usuarios chega como "= 5" (meus fóruns) ou "<> 5" (outros fóruns)
            $texto = trim((string)$this->getId_usuarios());
            $operador = (strpos($texto, "<>") === 0) ? "<>" : "=";
            $idUser = (int)preg_replace('/\D/', '', $texto);
            $condic = " id_usuarios ".$operador." ?";

            if ($this->getLimite() >= 0) {
                $limit = " LIMIT ?, 8";
            }
            else {
                $limit = "";
            }
            $resposta = array($column, $condic, $limit, $idUser);
            return $resposta;
        }
        
        public function abrir() {
            $instru = $this->instrucao();
            if ($instru[0] == "" || trim($this->getTopico()) == "") {
                return; // tópico vazio ou periódico desconhecido
            }
            $this->setDt_aberta(date('Y-m-d'));
            $this->setH_aberta(date('H:i:s')); // H = hora 00-23, i = minutos
            $this->setSql(
                "INSERT INTO forum_pergunta (id_usuarios, ".$instru[0].", topico, detalhe, dt_aberta, hr_aberta) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $conect = new Conexao($this->getSql(), array(
                $instru[3], (int)$this->getId_periodico(), $this->getTopico(),
                $this->getDetalhe(), $this->getDt_aberta(), $this->getH_aberta()
            ));
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function total() {
            $instru = $this->instrucao();
            $this->setSql(
                "SELECT COUNT(*) FROM forum_pergunta WHERE".$instru[1]." AND fechado = ?"
            );
            $conect = new Conexao($this->getSql(), array($instru[3], (int)$this->getFechado()));
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function listar() {
            $instru = $this->instrucao();
            $this->setSql(
                "SELECT * FROM forum_pergunta WHERE".$instru[1]." AND fechado = ? ORDER BY dt_aberta DESC, hr_aberta DESC".$instru[2]
            );
            $params = array($instru[3], (int)$this->getFechado());
            if ($instru[2] != "") {
                $params[] = (int)$this->getLimite();
            }
            $conect = new Conexao($this->getSql(), $params);
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
        
        public function busca ($perg) {
            $this->setId_pergunta((int)$perg);
            $this->setSql(
                "SELECT forum_pergunta.*, usuarios.nome FROM forum_pergunta JOIN usuarios ON forum_pergunta.id_usuarios = usuarios.id_usuarios WHERE forum_pergunta.id_pergunta = ?"
            );
            $conect = new Conexao($this->getSql(), array($this->getId_pergunta()));
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
    }
?>