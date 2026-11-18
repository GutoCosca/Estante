<?php
    // Faz a conexão com o BD e executa os comandos com prepared statements.
    // Uso: $c = new Conexao("SELECT * FROM livros WHERE id_livros = ?", array($id));
    //      $c->conectar();
    // Os valores em $params substituem os "?" na ordem; o MySQL trata cada um
    // como dado (nunca como comando), o que impede SQL injection.
    class Conexao {
        private static $link = null; // uma única conexão reaproveitada na página
        private $servidor;
        private $usuario;
        private $senha;
        private $db;
        private $sql;
        private $params;
        private $result;

        public function __construct($sql, $params = array()) {
            // No Docker o banco fica no serviço "db"; fora dele continua "localhost"
            $this->setServidor(getenv("DB_HOST") ?: "localhost");
            $this->setUsuario(getenv("DB_USER") ?: "estante_app");
            $this->setSenha(getenv("DB_PASS") ?: "");
            $this->setDb("biblioteca");
            $this->setSql($sql);
            $this->setParams($params);
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

        public function getParams() {
            return $this->params;
        }

        public function setParams($params) {
            $this->params = is_array($params) ? array_values($params) : array();
        }

        public function getResult() {
            return $this->result;
        }

        public function setResult($result) {
                $this->result = $result;
        }

        private function link() {
            if (self::$link === null) {
                // erros do MySQL viram exceções (padrão no PHP 8.1+, garantido aqui para versões antigas)
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                self::$link = mysqli_connect($this->getServidor(), $this->getUsuario(), $this->getSenha(), $this->getDb());
            }
            return self::$link;
        }

        // Executa o comando. SELECT -> getResult() é um mysqli_result;
        // INSERT/UPDATE/DELETE -> getResult() é true.
        public function conectar(){
            try {
                $stmt = mysqli_prepare($this->link(), $this->getSql());
                $params = $this->getParams();
                if (count($params) > 0) {
                    $tipos = "";
                    foreach ($params as $valor) {
                        if (is_int($valor) || is_bool($valor)) {
                            $tipos .= "i";
                        }
                        elseif (is_float($valor)) {
                            $tipos .= "d";
                        }
                        else {
                            $tipos .= "s"; // texto, datas e NULL
                        }
                    }
                    mysqli_stmt_bind_param($stmt, $tipos, ...$params);
                }
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $this->setResult($resultado !== false ? $resultado : true);
            }
            catch (mysqli_sql_exception $erro) {
                error_log("Erro SQL: ".$erro->getMessage()." | ".$this->getSql());
                exit("Não foi possível localizar.<br> Tente novamente");
            }
        }

        // Id gerado pelo último INSERT
        public static function ultimoId() {
            return self::$link ? mysqli_insert_id(self::$link) : 0;
        }
    }
?>
