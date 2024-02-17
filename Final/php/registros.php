<?php
    require_once ('conexao.php');
    require_once ('usuarios.php');

    //lista e busca os arquivos do BD
    class Registros {
        private $sql;
        private $tabela;
        private $codigo;
        private $tbl;
        private $tipo;
        private $letra;
        private $ordem;
        private $arqEmprestar;
        private $arqMorto;
        private $id_user;
        private $limite;

        public function __construct($user, $tb, $tp, $lt, $od, $emprestar, $morto, $lmt) {
            $this->setId_user($user);
            $this->setTabela($tb);
            $this->setTipo($tp);
            $this->setLimite($lmt);
            $this->setArqMorto($morto);
            $this->setArqEmprestar($emprestar);
            $this->setOrdem($od);
            $this->setLetra($lt);
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

        public function getArqMorto() {
            return $this->arqMorto;
        }

        public function setArqMorto($morto) {
            $this->arqMorto = $morto;
        }

        public function getArqEmprestar() {
            return $this->arqEmprestar;
        }

        public function setArqEmprestar($emprestar) {
            $this->arqEmprestar = $emprestar;
        }

        public function getLimite() {
            return $this->limite;
        }

        public function setLimite($lmt) {
            $this->limite = $lmt;
        }

        public function instrucao () {
             if ($this->getTabela() === "livros") {
                $colum6 = ", ano ";
                $colum1 = " id_livros = ";
             }
             elseif ($this->getTabela() === "revistas") {
                $colum6 = ", numero ";
                $colum1 = " id_revista = ";
             }

             if ($this->getLetra() != null) {
                $leter = " AND ".$this->getTipo()." LIKE '".$this->getLetra()."%'";
             }
             else {
                $leter = "";
             }

             if ($this->getOrdem() != null) {
                $ordenar = $this->getOrdem();
             }
             else 
                $ordenar = " ASC";
             return $instru = array($colum6, $leter, $ordenar, $colum1);
        }

        public function total () {
            $complemento = $this->instrucao();
            $this->setSql("SELECT COUNT(*) FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND arqempresta = ".$this->getArqEmprestar()." AND arqmorto = ".$this->getArqMorto().$complemento[1]);
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
            return $this->getSql();
        }

        public function lista() {
            $complemento = $this->instrucao();
            $this->setSql("SELECT * FROM ". $this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND arqempresta = ".$this->getArqEmprestar()." AND arqmorto = ".$this->getArqMorto().$complemento[1]." ORDER BY ". $this->getTipo()." ".$complemento[2].$complemento[0].$complemento[2]." LIMIT ".$this->getLimite().",10");
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
            return $this->getSql();
        }

        public function buscar($busCod) {
            $complemento = $this->instrucao();
            $this->setCodigo($busCod);
            $this->setSql("SELECT * FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND ".$complemento[3].$this->getCodigo());
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
    }

        //adicionar e modificar registros livros
    class EditLivros extends Registros {
        private $id_user;
        private $livro;
        private $autor;
        private $editora;
        private $edicao;
        private $ano;
        private $isbn;
        private $compra;
        private $sinopse;
        private $opiniao;
        private $arqEmprestar;
        private $arqMorto;
        private $capaTemp;
        private $capaSize;
        private $capaExt;
        private $capaNome;

        public function __construct($user,$tb, $li, $au, $ed, $edi, $ano, $isbn, $dtComp, $cT, $cS, $cX, $res, $opn, $emprestar, $morto) {
            $this->setId_user($user);
            $this->setTabela($tb);
            $this->setLivro(addslashes($li));
            $this->setAutor(addslashes($au));
            $this->setEditora(addslashes($ed));
            $this->setEdicao(addslashes($edi));
            $this->setAno($ano);
            $this->setIsbn($isbn);
            $this->setCompra($dtComp);
            $this->setSinopse(addslashes($res));
            $this->SetOpiniao(addslashes($opn));
            $this->setArqEmprestar($emprestar);
            $this->setArqMorto($morto);
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

        public function getArqEmprestar() {
            return $this->arqEmprestar;
        }

        public function setArqEmprestar($emprestar) {
            $this->arqEmprestar = $emprestar;
        }
        
        public function getArqMorto() {
            return $this->arqMorto;
        }

        public function setArqMorto($morto) {
            $this->arqMorto = $morto;
        }
        
        public function getSinopse() {
            return $this->sinopse;
        }

        public function setSinopse($res) {
            $this->sinopse = $res;
        }
        
        public function getOpiniao() {
            return $this->opiniao;
        }

        public function setOpiniao($opn) {
            $this->opiniao = $opn;
        }

        private function instrucoes() {
            $instAnoColum = "";
            $instAnoValue = "";
            $instAnoValue2 = "";
            $instAno = "";
            $instCompColum = "";
            $instCompValue = "";
            $instCompValue2 = "";
            $instComp = "";
            $diretorio = "capas/";
            $instrCapaColum = "";
            $instrCapaValue = "";
            $instrCapaValue2 = "";
            $instCapa = "";
            if($this->getAno() != null) {
                $instAnoColum = ", ano";
                $instAnoValue = ", ".$this->getAno();
                $instAnoValue2 = $this->getAno();
                $instAno = " = ";
            }
            
            if($this->getCompra() != null){
                $instCompColum = ", compra";
                $instCompValue = ", '".$this->getCompra()."'";
                $instCompValue2 = " '".$this->getCompra()."'";
                $instComp = " = ";
            }
            
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
            if ($this->getTabela() === "livros") {
                $this->setSql("INSERT INTO ".$this->getTabela()." (id_usuarios, livro, autor, editora, edicao".$instrucao[0].", isbn".$instrucao[2].$instrucao[5].", sinopse, opiniao, arqempresta, arqmorto) VALUES (".$this->getId_user().", '".$this->getLivro()."', '".$this->getAutor()."', '".$this->getEditora()."', '".$this->getEdicao()."'".$instrucao[1].", '".$this->getIsbn()."'".$instrucao[3].$instrucao[6].", '".$this->getSinopse()."', '".$this->getOpiniao()."', ".$this->getArqEmprestar().", ".$this->getArqMorto().")");
            }
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
        }

        public function altLivro($cod) {
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
            $this->setSql("UPDATE ".$this->getTabela()." SET livro = '".$this->getLivro()."', autor = '".$this->getAutor()."', editora = '".$this->getEditora()."', edicao = '".$this->getEdicao()."'".$instrucao[0].$instrucao[7].$instrucao[10].", isbn = '".$this->getIsbn()."'".$instrucao[2].$instrucao[8].$instrucao[11].$instrucao[5].$instrucao[9].$instrucao[12].", sinopse = '".$this->getSinopse()."', opiniao = '".$this->getOpiniao()."', arqempresta = ".$this->getArqEmprestar().", arqmorto = ".$this->getArqMorto()." WHERE id_livros = ".$this->getCodigo());
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
            return $this->getSql();
        }
    }

    //adicionar e modificar registros revistas
    class EditRevista extends Registros {
        private $id_user;
        private $revista;
        private $numero;
        private $titulo;
        private $autor;
        private $editora;
        private $ano;
        private $issn;
        private $compra;
        private $sinopse;
        private $opiniao;
        private $arqEmprestar;
        private $arqMorto;
        private $capaTemp;
        private $capaSize;
        private $capaExt;
        private $capaNome;
        
        public function __construct($user,$tb, $re, $num, $tit, $au, $ed, $ano, $issn, $dtComp, $cT, $cS, $cX, $res, $opn, $emprestar, $morto) {
            $this->setId_user($user);
            $this->setTabela($tb);
            $this->setRevista(addslashes($re));
            $this->setNumero($num);
            $this->setTitulo(addslashes($tit));
            $this->setAutor(addslashes($au));
            $this->setEditora(addslashes($ed));
            $this->setAno($ano);
            $this->setIssn($issn);
            $this->setCompra($dtComp);
            $this->setSinopse(addslashes($res));
            $this->SetOpiniao(addslashes($opn));
            $this->setArqEmprestar($emprestar);
            $this->setArqMorto($morto);
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

        public function getRevista() {
            return $this->revista;
        }

        public function setRevista($re) {
                $this->revista = $re;
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

        public function getNumero() {
                return $this->numero;
        }

        public function setNumero($num) {
                $this->numero = $num;
        }

        public function getAno() {
                return $this->ano;
        }

        public function setAno($ano) {
                $this->ano = $ano;
        }

        public function getTitulo() {
                return $this->titulo;            
        }

        public function setTitulo($tit) {
                $this->titulo = $tit;
        }

        public function getIssn() {
                return $this->issn;
        }

        public function setIssn($issn) {
                $this->issn = $issn;
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

        public function getArqEmprestar() {
            return $this->arqEmprestar;
        }

        public function setArqEmprestar($emprestar) {
            $this->arqEmprestar = $emprestar;
        }
        
        public function getArqMorto() {
            return $this->arqMorto;
        }

        public function setArqMorto($morto) {
            $this->arqMorto = $morto;
        }
        
        public function getSinopse() {
            return $this->sinopse;
        }

        public function setSinopse($res) {
            $this->sinopse = $res;
        }
        
        public function getOpiniao() {
            return $this->opiniao;
        }

        public function setOpiniao($opn) {
            $this->opiniao = $opn;
        }

        private function instrucoes() {
            $instAnoColum = "";
            $instAnoValue = "";
            $instAnoValue2 = "";
            $instAno = "";
            $diretorio = "capas/";
            $instrCapaColum = "";
            $instrCapaValue = "";
            $instrCapaValue2 = "";
            $instCapa = "";
            $instCompColum = "";
            $instCompValue = "";
            $instCompValue2 = "";
            $instComp = "";
            if($this->getAno() != null) {
                $instAnoColum = ", ano";
                $instAnoValue = ", ".$this->getAno();
                $instAnoValue2 = $this->getAno();
                $instAno = " = ";
            }
            
            if($this->getCompra() != null){
                $instCompColum = ", compra";
                $instCompValue = ", '".$this->getCompra()."'";
                $instCompValue2 = " '".$this->getCompra()."'";
                $instComp = " = ";
            }
            
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

        public function addRevistas () {
            $instrucao = $this->instrucoes();
            if ($this->getTabela() === "revistas") {
                $this->setSql("INSERT INTO ".$this->getTabela()." (id_usuarios, revista, titulo, autor, editora, numero".$instrucao[0].", issn".$instrucao[2].$instrucao[5].", sinopse, opiniao, arqempresta ,arqmorto) VALUES (".$this->getId_user().", '".$this->getRevista()."', '".$this->getTitulo()."','".$this->getAutor()."', '".$this->getEditora()."', '".$this->getNumero()."'".$instrucao[1].", '".$this->getIssn()."'".$instrucao[3].$instrucao[6].", '".$this->getSinopse()."', '".$this->getOpiniao()."', ".$this->getArqEmprestar()."', ".$this->getArqMorto().")");
            }
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
        }

        public function altRevista($cod) {
            $this->setCodigo($cod);
            $instrucao = $this->instrucoes();
            if($this->getCapaSize() > 0){
                $sqlCapa = "SELECT capa FROM ".$this->getTabela()." WHERE id_revistas = ".$this->getCodigo();
                $conectCapa = new Conexao($sqlCapa);
                $conectCapa->conectar();
                $resultCapa = mysqli_fetch_assoc($conectCapa->getResult());
                $capaAtual = $resultCapa["capa"];
                
                if($capaAtual != null) {
                    unlink($instrucao[4].$capaAtual);
                }
            }
            $this->setSql("UPDATE ".$this->getTabela()." SET revista = '".$this->getRevista()."', titulo = '".$this->getTitulo()."', autor = '".$this->getAutor()."', editora = '".$this->getEditora()."', numero = '".$this->getNumero()."'".$instrucao[0].$instrucao[7].$instrucao[10].", issn = '".$this->getIssn()."'".$instrucao[2].$instrucao[8].$instrucao[11].$instrucao[5].$instrucao[9].$instrucao[12].", sinopse = '".$this->getSinopse()."', opiniao = '".$this->getOpiniao()."', arqempresta = ".$this->getArqEmprestar()."', arqmorto = ".$this->getArqMorto()." WHERE id_livros = ".$this->getCodigo());
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
            return $this->getSql();
        }
    }

    class Emprestar {
        private $id_emprest;
        private $id_usuario;
        private $id_livros;
        private $id_revistas;
        private $nome;
        private $dt_saida;
        private $dt_entra;

        public function getId_emprest() {
                return $this->id_emprest;
        }

        public function setId_emprest($id_emprest) {
                $this->id_emprest = $id_emprest;
        }

        public function getId_usuario() {
                return $this->id_usuario;
        }

        public function setId_usuario($id_usuario) {
                $this->id_usuario = $id_usuario;
        }        

        public function getId_livros() {
                return $this->id_livros;
        }

        public function setId_livros($id_livros) {
                $this->id_livros = $id_livros;
        }

        public function getId_revistas() {
                return $this->id_revistas;
        }

        public function setId_revistas($id_revistas) {
                $this->id_revistas = $id_revistas;
        }

        public function getNome() {
                return $this->nome;
        }

        public function setNome($nome) {
                $this->nome = $nome;
        }

        public function getDt_saida() {
                return $this->dt_saida;
        }

        public function setDt_saida($dt_saida) {
                $this->dt_saida = $dt_saida;
        }

        public function getDt_entra() {
                return $this->dt_entra;
        }

        public function setDt_entra($dt_entra) {
                $this->dt_entra = $dt_entra;
        }
    }

    class Atividade {
        private $last_time;

        public function getLast_time() {
                return $this->last_time;
        }

        public function setLast_time($last_time) {
                $this->last_time = $last_time;
        }
        
        public function tempo() {
            $this->getLast_time($_SESSION['last_time']);
            if (isset($_SESSION['last_time']) && (time() - $_SESSION['last_time'] > 1800)) {
                header('location:login.php');
            }
            $_SESSION['last_time'] = time();
        }
    }
?>