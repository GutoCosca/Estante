<?php
    require_once ('conexao.php');

    //lista e busca os arquivos do BD
    class Registros {
        private $sql;
        private $tabela;
        private $codigo;
        private $tbl;
        private $tipo;
        private $letra;
        private $ordem;
        private $id_user;

        public function __construct($user, $tb, $tp, $lt, $od) {
            $this->setId_user($user);
            $this->setTabela($tb);
            $this->setTipo($tp);
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
        
        public function getId_user() {
            return $this->id_user;
        }

        public function setId_user($user) {
            $this->id_user = $user;
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

        public function lista() {
            if ($this->getLetra() != null) {
                if ($this->getTipo() == "livro" || $this->getTipo() == "autor") {
                    $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND ".$this->getTipo()." LIKE '".$this->getLetra()."%' ORDER BY ".$this->getTipo()." ".$this->getOrdem().",  ano ".$this->getOrdem());
                }
                else if ($this->getTipo() == "revista" || $this->getTipo() == "titulo") {
                    
                    $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND ".$this->getTipo()." LIKE '".$this->getLetra()."%' ORDER BY ".$this->getTipo()." ".$this->getOrdem().",  numero ".$this->getOrdem());
                }
            }
            else {
                if ($this->getTipo() == "livro" || $this->getTipo() == "autor") {
                    $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." ORDER BY ".$this->getTipo()." ".$this->getOrdem().", ano ".$this->getOrdem());
                }
                elseif ($this->getTipo() == "revista" || $this->getTipo() == "titulo"){
                    $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." ORDER BY ".$this->getTipo()." ".$this->getOrdem().", numero ".$this->getOrdem());
                }
            }
            
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
            //return $this->getSql();
        }

        public function buscar($busCod) {
            $this->setCodigo($busCod);
            
            $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND id_livros = ".$this->getCodigo());
            
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
    }

        //adicionar e modificar registros
    class Editar extends Registros {
        private $id_user;
        private $livro;
        private $autor;
        private $editora;
        private $edicao;
        private $ano;
        private $isbn;
        private $compra;
        private $capaTemp;
        private $capaSize;
        private $capaExt;
        private $capaNome;

        public function __construct($user,$tb, $li, $au, $ed, $edi, $ano, $isbn, $dtComp, $cT, $cS, $cX) {
            $this->setId_user($user);
            $this->setTabela($tb);
            $this->setLivro(addslashes($li));
            $this->setAutor(addslashes($au));
            $this->setEditora(addslashes($ed));
            $this->setEdicao(addslashes($edi));
            $this->setAno($ano);
            $this->setIsbn($isbn);
            $this->setCompra($dtComp);
            $this->setCapaTemp($cT);
            $this->setCapaSize($cS);
            $this->setCapaExt(strtolower($cX));
        }

        public function getId_user() {
            return $this->id_user;
        }

        public function setId_user($user) {
            $this->id_user = $user;
        }

        public function getLivro() {
            return $this->livro;
        }

        public function setLivro($li) {
                $this->livro = $li;
            }

        public function getAutor() {
                return $this->autor;
        }

        public function setAutor($au) {
                $this->autor = $au;
        }

        public function getEditora() {
            return $this->editora;
        }

        public function setEditora($ed) {
                $this->editora = $ed;
        }

        public function getEdicao() {
                return $this->edicao;
        }

        public function setEdicao($edi) {
                $this->edicao = $edi;
        }

        public function getAno() {
                return $this->ano;
        }

        public function setAno($ano) {
                $this->ano = $ano;
        }

        public function getIsbn() {
                return $this->isbn;
        }

        public function setIsbn($isbn) {
                $this->isbn = $isbn;
        }

        public function getCompra() {
                return $this->compra;
        }

        public function setCompra($dtComp) {
                $this->compra = $dtComp;
        }

        public function getCapaTemp() {
                return $this->capaTemp;
        }

        public function setCapaTemp($cT) {
                $this->capaTemp = $cT;
        }

        public function getCapaSize() {
                return $this->capaSize;
        }

        public function setCapaSize($cS) {
                $this->capaSize = $cS;
        }

        public function getCapaExt() {
                return $this->capaExt;
        }

        public function setCapaExt($cE) {
                $this->capaExt = $cE;
        }

        public function getCapaNome() {
                return $this->capaNome;
        }

        public function setCapaNome($cN) {
                $this->capaNome = $cN;
        }

        private function instrucoes() {
            $instAnoColum = "";
            $instAnoValue = "";
            $instAnoValue2 = "";
            $instAno = "";
            
            if($this->getAno() != null) {
                $instAnoColum = ", ano";
                $instAnoValue = ", ".$this->getAno();
                $instAnoValue2 = $this->getAno();
                $instAno = " = ";
            }

            $instCompColum = "";
            $instCompValue = "";
            $instCompValue2 = "";
            $instComp = "";
            
            if($this->getCompra() != null){
                $instCompColum = ", compra";
                $instCompValue = ", '".$this->getCompra()."'";
                $instCompValue2 = " '".$this->getCompra()."'";
                $instComp = " = ";
            }

            $diretorio = "capas/";
            $instrCapaColum = "";
            $instrCapaValue = "";
            $instrCapaValue2 = "";
            $instCapa = "";
            
            if($this->getCapaSize() > 0){
                $this->setCapaNome(uniqid().".".$this->getCapaExt());
                $instrCapaColum = ", capa";
                $instrCapaValue = ", '".$this->getCapaNome()."'";
                $instrCapaValue2 = " '".$this->getCapaNome()."'";
                $instCapa = " = ";
                move_uploaded_file($this->getCapaTemp(), $diretorio.$this->getCapaNome());
            }

            return $instru = array($instAnoColum, $instAnoValue, $instCompColum, $instCompValue, $diretorio, $instrCapaColum, $instrCapaValue, $instAno, $instComp, $instCapa, $instAnoValue2, $instCompValue2, $instrCapaValue2);
        }

        public function addLivros () {
            $instrucao = $this->instrucoes();

            $this->setSql("INSERT INTO ".$this->getTabela()." (id_usuarios, livro, autor, editora, edicao".$instrucao[0].", isbn".$instrucao[2].$instrucao[5].") VALUES (".$this->getId_user().", '".$this->getLivro()."', '".$this->getAutor()."', '".$this->getEditora()."', '".$this->getEdicao()."'".$instrucao[1].", '".$this->getIsbn()."'".$instrucao[3].$instrucao[6].")");
            
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
            return $this->getSql();
        }

        public function alterar($cod) {
            $this->setCodigo($cod);
            $instrucao = $this->instrucoes();
            
            if($this->getCapaSize() > 0){
                $sqlCapa = "SELECT capa FROM ".$this->getTabela()." WHERE id_livros = ".$this->getCodigo();
                $conectCapa = new Conexao($sqlCapa);
                $conectCapa->conectar();
                $resultCapa = mysqli_fetch_assoc($conectCapa->getResult());
                $capaAtual = $resultCapa["capa"];
                
                if($capaAtual != null) {
                    unlink($instrucao[4].$capaAtual);
                }
            }

            $this->setSql("UPDATE ".$this->getTabela()." SET livro = '".$this->getLivro()."', autor = '".$this->getAutor()."', editora = '".$this->getEditora()."', edicao = '".$this->getEdicao()."'".$instrucao[0].$instrucao[7].$instrucao[10].", isbn = '".$this->getIsbn()."'".$instrucao[2].$instrucao[8].$instrucao[11].$instrucao[5].$instrucao[9].$instrucao[12]." WHERE id_livros = ".$this->getCodigo());

            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
        }
        
    }
?>