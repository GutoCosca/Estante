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

        public function __construct($user, $tb, $tp, $lt, $od, $eb, $emprestar, $morto, $lmt) {
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
                $colum1 = " id_revistas = ";
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
            $this->setSql(
                "SELECT COUNT(*) FROM ".$this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND arqempresta = ".$this->getArqEmprestar()." AND arqmorto = ".$this->getArqMorto().$complemento[1]
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function lista() {
            $complemento = $this->instrucao();
            $this->setSql(
                "SELECT * FROM ". $this->getTabela()." WHERE id_usuarios = ".$this->getId_user()." AND arqempresta = ".$this->getArqEmprestar()." AND arqmorto = ".$this->getArqMorto().$complemento[1]." ORDER BY ". $this->getTipo()." ".$complemento[2].$complemento[0].$complemento[2]." LIMIT ".$this->getLimite().",10"
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function buscar($busCod) {
            $complemento = $this->instrucao();
            $this->setCodigo($busCod);
            $this->setSql(
                "SELECT * FROM ".$this->getTabela()." WHERE ".$complemento[3].$this->getCodigo()
            );
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
        private $ebook;
        private $arqEmprestar;
        private $arqMorto;
        private $capaTemp;
        private $capaSize;
        private $capaExt;
        private $capaNome;

        public function __construct($user,$tb, $li, $au, $ed, $edi, $ano, $isbn, $dtComp, $cT, $cS, $cX, $res, $opn, $eb, $emprestar, $morto) {
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
            $this->setOpiniao(addslashes($opn));
            $this->setEbook($eb);
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

        public function getEbook() {
            return $this->ebook;
        }

        public function setEbook($eb) {
            $this->ebook = $eb;
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
            $instEbookColum = "";
            $instEbookValue = "";
            if ($this->getAno() != null) {
                $instAnoColum = ", ano";
                $instAnoValue = ", ".$this->getAno();
                $instAnoValue2 = $this->getAno();
                $instAno = " = ";
            }
            
            if ($this->getCompra() != null){
                $instCompColum = ", compra";
                $instCompValue = ", '".$this->getCompra()."'";
                $instCompValue2 = " '".$this->getCompra()."'";
                $instComp = " = ";
            }
            
            if ($this->getCapaSize() > 0){
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
                $this->setSql(
                    "INSERT INTO ".$this->getTabela()." (id_usuarios, livro, autor, editora, edicao".$instrucao[0].", isbn".$instrucao[2].$instrucao[5].", sinopse, opiniao, ebook, arqempresta, arqmorto) VALUES (".$this->getId_user().", '".$this->getLivro()."', '".$this->getAutor()."', '".$this->getEditora()."', '".$this->getEdicao()."'".$instrucao[1].", '".$this->getIsbn()."'".$instrucao[3].$instrucao[6].", '".$this->getSinopse()."', '".$this->getOpiniao()."', ".$this->getEbook().", ".$this->getArqEmprestar().", ".$this->getArqMorto().")"
                );
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
            $this->setSql(
                "UPDATE ".$this->getTabela()." SET livro = '".$this->getLivro()."', autor = '".$this->getAutor()."', editora = '".$this->getEditora()."', edicao = '".$this->getEdicao()."'".$instrucao[0].$instrucao[7].$instrucao[10].", isbn = '".$this->getIsbn()."'".$instrucao[2].$instrucao[8].$instrucao[11].$instrucao[5].$instrucao[9].$instrucao[12].", sinopse = '".$this->getSinopse()."', opiniao = '".$this->getOpiniao()."', ebook = ".$this->getEbook().", arqempresta = ".$this->getArqEmprestar().", arqmorto = ".$this->getArqMorto()." WHERE id_livros = ".$this->getCodigo()
            );
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
            return $this->getSql();
        }
    }

    //adicionar e modificar registros revistas
    class EditRevistas extends Registros {
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
        private $ebook;
        private $arqEmprestar;
        private $arqMorto;
        private $capaTemp;
        private $capaSize;
        private $capaExt;
        private $capaNome;
        
        public function __construct($user,$tb, $re, $num, $tit, $au, $ed, $ano, $issn, $dtComp, $cT, $cS, $cX, $res, $opn, $eb, $emprestar, $morto) {
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
            $this->setEbook($eb);
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

        public function getEbook() {
            return $this->ebook;    
        }

        public function setEbook($eb) {
            $this->ebook = $eb;
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
            $instEbookColum = "";
            $instEbookValue = "";
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
                $this->setSql(
                    "INSERT INTO ".$this->getTabela()." (id_usuarios, revista, titulo, autor, editora, numero".$instrucao[0].", issn".$instrucao[2].$instrucao[5].", sinopse, opiniao, ebook, arqempresta ,arqmorto) VALUES (".$this->getId_user().", '".$this->getRevista()."', '".$this->getTitulo()."','".$this->getAutor()."', '".$this->getEditora()."', '".$this->getNumero()."'".$instrucao[1].", '".$this->getIssn()."'".$instrucao[3].$instrucao[6].", '".$this->getSinopse()."', '".$this->getOpiniao()."', ".$this->getEbook().", ".$this->getArqEmprestar().", ".$this->getArqMorto().")"
                );
            }
            $Conect = new Conexao($this->getSql());
            $Conect->conectar();
            $this->setTbl($Conect->getResult());
        }

        public function altRevistas($cod) {
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
            $this->setSql(
                "UPDATE ".$this->getTabela()." SET revista = '".$this->getRevista()."', titulo = '".$this->getTitulo()."', autor = '".$this->getAutor()."', editora = '".$this->getEditora()."', numero = '".$this->getNumero()."'".$instrucao[0].$instrucao[7].$instrucao[10].", issn = '".$this->getIssn()."'".$instrucao[2].$instrucao[8].$instrucao[11].$instrucao[5].$instrucao[9].$instrucao[12].", sinopse = '".$this->getSinopse()."', opiniao = '".$this->getOpiniao()."', ebook = ".$this->getEbook().", arqempresta = ".$this->getArqEmprestar().", arqmorto = ".$this->getArqMorto()." WHERE id_revistas = ".$this->getCodigo()
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }
    }

    class Emprestar {
        private $sql;
        private $tbl;
        private $id_emprest;
        private $id_usuario;
        private $id_livros;
        private $id_revistas;
        private $nome;
        private $dt_saida;
        private $dt_entra;
        private $limite;

        public function __construct($user, $id_emprest, $li, $re, $nome, $sai, $entra, $lt) {
            $this->setId_usuario($user);
            $this->setId_emprest($id_emprest);
            $this->setId_livros($li);
            $this->setId_revistas($re);
            $this->setNome(addslashes($nome));
            $this->setDt_saida($sai);
            $this->setDt_entra($entra);
            $this->setLimite($lt);
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

        public function getLimite() {
            return $this->limite;
        }

        public function setLimite($limite) {
            $this->limite = $limite;
        }

        public function instrucao() {
            if ($this->getId_livros() != null) {
                $periodico = 'id_livros = '.$this->getId_livros();
            }
            elseif ($this->getId_revistas() != null) {
                $periodico = 'id_revistas = '.$this->getId_revistas();
            }
            return $periodico;
        }

        public function total() {
            $periodico = $this->instrucao();
            $instruId = $this->instrucaoID();
            $this->setSql(
                "SELECT COUNT(*) FROM emprestimo WHERE id_usuarios = ".$this->getId_usuario().$instruId[4]." AND ".$periodico
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function listar() {
            $periodico = $this->instrucao();
            $instruId = $this->instrucaoID();
            $this->setSql(
                "SELECT * FROM emprestimo WHERE id_usuarios = ".$this->getId_usuario().$instruId[4]." AND ".$periodico. " ORDER BY dt_emprest DESC ".$instruId[5]
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function instrucaoID() {
            if ($this->getId_livros() != null) {
                $instru01 = "id_livros";
                $instru02 = $this->getId_livros();
            }
            elseif ($this->getId_revistas() != null) {
                $instru01 = "id_revistas";
                $instru02 = $this->getId_revistas();
            }

            if ($this->dt_entra != null) {
                $instru03 = ", dt_devol";
                $instru04 = ", '".$this->getDt_entra()."'";
            }
            else {
                $instru03 = "";
                $instru04 = "";
            }

            if ($this->getId_emprest() != null) {
                $instru05 = " AND id_emprest = ".$this->getId_emprest();
            }
            else {
                $instru05 = "";
            }

            if ($this->getLimite() != null) {
                $instru06 = "LIMIT ".$this->getLimite();
            }
            else {
                $instru06 = "";
            }
            $instrucaoID = array($instru01, $instru02, $instru03, $instru04, $instru05, $instru06);
            return $instrucaoID;
        }

        public function addEmprest() {
            $instruId = $this->instrucaoID();
            $this->setSql(
                "INSERT INTO emprestimo (id_usuarios, ".$instruId[0].", nome, dt_emprest".$instruId[2].") VALUES (".$this->getId_usuario().", ".$instruId[1].", '".$this->getNome()."', '".$this->getDt_saida()."'".$instruId[3].")"
            );
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function altEmprest() {
            $this->setSql("UPDATE emprestimo SET nome = '".$this->getNome()."', dt_emprest = '".$this->getDt_saida()."', dt_devol = '".$this->getDt_entra()."' WHERE id_emprest = ".$this->getId_emprest());
            $conect = new Conexao($this->getSql());
            $conect->conectar();
            $mensagem = "";
        }

    }

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
                session_unset();
                session_destroy();
                header('location:principal.html');
            }
            $_SESSION['last_time'] = time();
        }
    }
?>