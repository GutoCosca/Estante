<?php

require_once ('conexao.php');
require_once ('usuarios.php');

    // Retorna a data no formato Y-m-d ou null se for inválida/vazia
    function dataSql($data) {
        $d = DateTime::createFromFormat('Y-m-d', (string)$data);
        return ($d && $d->format('Y-m-d') === $data) ? $data : null;
    }

    // Retorna número inteiro ou null se vazio
    function intOuNull($valor) {
        return ($valor === null || $valor === '') ? null : (int)$valor;
    }

    // Valida a imagem de capa enviada: só aceita imagens de verdade
    function capaValida($temp, $tamanho, $ext) {
        $permitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if ($tamanho <= 0 || $tamanho > 5 * 1024 * 1024) {
            return false;
        }
        if (!in_array(strtolower((string)$ext), $permitidas, true)) {
            return false;
        }
        return @getimagesize($temp) !== false;
    }

    // Apaga a capa antiga do registro (somente se for do usuário)
    function apagarCapa($tabela, $colunaId, $codigo, $idUser) {
        $conect = new Conexao("SELECT capa FROM ".$tabela." WHERE ".$colunaId." = ? AND id_usuarios = ?", array((int)$codigo, (int)$idUser));
        $conect->conectar();
        $linha = mysqli_fetch_assoc($conect->getResult());
        $capaAtual = $linha["capa"] ?? null;
        if ($capaAtual != null && is_file("capas/".basename($capaAtual))) {
            unlink("capas/".basename($capaAtual));
        }
    }

    // Monta e executa INSERT a partir de um array coluna => valor
    function inserir($tabela, $dados) {
        $colunas = implode(", ", array_keys($dados));
        $marcas = implode(", ", array_fill(0, count($dados), "?"));
        $conect = new Conexao("INSERT INTO ".$tabela." (".$colunas.") VALUES (".$marcas.")", array_values($dados));
        $conect->conectar();
        return $conect;
    }

    // Monta e executa UPDATE a partir de um array coluna => valor
    function atualizar($tabela, $dados, $colunaId, $codigo, $idUser) {
        $sets = array();
        foreach (array_keys($dados) as $coluna) {
            $sets[] = $coluna." = ?";
        }
        $params = array_values($dados);
        $params[] = (int)$codigo;
        $params[] = (int)$idUser;
        $conect = new Conexao("UPDATE ".$tabela." SET ".implode(", ", $sets)." WHERE ".$colunaId." = ? AND id_usuarios = ?", $params);
        $conect->conectar();
        return $conect;
    }

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
             if ($this->getTabela() === "revistas") {
                $colum6 = ", numero ";
                $colum1 = " id_revistas = ";
                $colunas = array("revista", "titulo");
             }
             else {
                $this->setTabela("livros");
                $colum6 = ", ano ";
                $colum1 = " id_livros = ";
                $colunas = array("livro", "autor");
             }

             // Nomes de coluna não podem ir como "?": só aceita colunas conhecidas
             if (!in_array($this->getTipo(), $colunas, true)) {
                $this->setTipo($colunas[0]);
             }

             $params = array();
             if ($this->getLetra() != null) {
                $leter = " AND ".$this->getTipo()." LIKE ?";
                // % e _ digitados pelo usuário são tratados como texto comum
                $params[] = addcslashes($this->getLetra(), "\\%_")."%";
             }
             else {
                $leter = "";
             }

             if (strtoupper((string)$this->getOrdem()) === "DESC") {
                $ordenar = " DESC";
             }
             else {
                $ordenar = " ASC";
             }
             return $instru = array($colum6, $leter, $ordenar, $colum1, $params);
        }

        public function total () {
            $complemento = $this->instrucao();
            $this->setSql(
                "SELECT COUNT(*) FROM ".$this->getTabela()." WHERE id_usuarios = ? AND arqempresta = ? AND arqmorto = ?".$complemento[1]
            );
            $params = array_merge(
                array((int)$this->getId_user(), (int)$this->getArqEmprestar(), (int)$this->getArqMorto()),
                $complemento[4]
            );
            $conect = new Conexao($this->getSql(), $params);
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function lista() {
            $complemento = $this->instrucao();
            $this->setSql(
                "SELECT * FROM ". $this->getTabela()." WHERE id_usuarios = ? AND arqempresta = ? AND arqmorto = ?".$complemento[1]." ORDER BY ". $this->getTipo().$complemento[2].$complemento[0].$complemento[2]." LIMIT ?, 10"
            );
            $params = array_merge(
                array((int)$this->getId_user(), (int)$this->getArqEmprestar(), (int)$this->getArqMorto()),
                $complemento[4],
                array(max(0, (int)$this->getLimite()))
            );
            $conect = new Conexao($this->getSql(), $params);
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function buscar($busCod) {
            $complemento = $this->instrucao();
            $this->setCodigo((int)$busCod);
            $params = array($this->getCodigo());
            // Se o usuário foi informado, só encontra registros dele
            $dono = "";
            if ($this->getId_user() != null) {
                $dono = " AND id_usuarios = ?";
                $params[] = (int)$this->getId_user();
            }
            $this->setSql(
                "SELECT * FROM ".$this->getTabela()." WHERE ".$complemento[3]."?".$dono
            );
            $conect = new Conexao($this->getSql(), $params);
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
            $this->setLivro((string)$li);
            $this->setAutor((string)$au);
            $this->setEditora((string)$ed);
            $this->setEdicao((string)$edi);
            $this->setAno(intOuNull($ano));
            $this->setIsbn((string)$isbn);
            $this->setCompra(dataSql($dtComp));
            $this->setSinopse((string)$res);
            $this->setOpiniao((string)$opn);
            $this->setEbook((int)$eb);
            $this->setArqEmprestar((int)$emprestar);
            $this->setArqMorto((int)$morto);
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

        // Salva a capa enviada (se for uma imagem válida) e devolve o nome do arquivo
        private function instrucoes() {
            if ($this->getCapaSize() > 0 && capaValida($this->getCapaTemp(), $this->getCapaSize(), $this->getCapaExt())) {
                $this->setCapaNome(uniqid().".".$this->getCapaExt());
                move_uploaded_file($this->getCapaTemp(), "capas/".$this->getCapaNome());
                return $this->getCapaNome();
            }
            return null;
        }

        public function addLivros () {
            $dados = array(
                "id_usuarios" => (int)$this->getId_user(),
                "livro" => $this->getLivro(),
                "autor" => $this->getAutor(),
                "editora" => $this->getEditora(),
                "edicao" => $this->getEdicao(),
                "isbn" => $this->getIsbn(),
                "sinopse" => $this->getSinopse(),
                "opiniao" => $this->getOpiniao(),
                "ebook" => (int)$this->getEbook(),
                "arqempresta" => (int)$this->getArqEmprestar(),
                "arqmorto" => (int)$this->getArqMorto()
            );
            if ($this->getAno() !== null) {
                $dados["ano"] = $this->getAno();
            }
            if ($this->getCompra() !== null) {
                $dados["compra"] = $this->getCompra();
            }
            $capa = $this->instrucoes();
            if ($capa !== null) {
                $dados["capa"] = $capa;
            }
            $Conect = inserir("livros", $dados);
            $this->setTbl($Conect->getResult());
        }

        public function altLivro($cod) {
            $this->setCodigo((int)$cod);
            $dados = array(
                "livro" => $this->getLivro(),
                "autor" => $this->getAutor(),
                "editora" => $this->getEditora(),
                "edicao" => $this->getEdicao(),
                "isbn" => $this->getIsbn(),
                "sinopse" => $this->getSinopse(),
                "opiniao" => $this->getOpiniao(),
                "ebook" => (int)$this->getEbook(),
                "arqempresta" => (int)$this->getArqEmprestar(),
                "arqmorto" => (int)$this->getArqMorto()
            );
            // ano e compra vazios mantêm o valor que já estava gravado
            if ($this->getAno() !== null) {
                $dados["ano"] = $this->getAno();
            }
            if ($this->getCompra() !== null) {
                $dados["compra"] = $this->getCompra();
            }
            if ($this->getCapaSize() > 0 && capaValida($this->getCapaTemp(), $this->getCapaSize(), $this->getCapaExt())) {
                apagarCapa("livros", "id_livros", $this->getCodigo(), $this->getId_user());
                $dados["capa"] = $this->instrucoes();
            }
            $Conect = atualizar("livros", $dados, "id_livros", $this->getCodigo(), $this->getId_user());
            $this->setTbl($Conect->getResult());
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
            $this->setRevista((string)$re);
            $this->setNumero((int)$num);
            $this->setTitulo((string)$tit);
            $this->setAutor((string)$au);
            $this->setEditora((string)$ed);
            $this->setAno(intOuNull($ano));
            $this->setIssn((string)$issn);
            $this->setCompra(dataSql($dtComp));
            $this->setSinopse((string)$res);
            $this->SetOpiniao((string)$opn);
            $this->setEbook((int)$eb);
            $this->setArqEmprestar((int)$emprestar);
            $this->setArqMorto((int)$morto);
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

        // Salva a capa enviada (se for uma imagem válida) e devolve o nome do arquivo
        private function instrucoes() {
            if ($this->getCapaSize() > 0 && capaValida($this->getCapaTemp(), $this->getCapaSize(), $this->getCapaExt())) {
                $this->setCapaNome(uniqid().".".$this->getCapaExt());
                move_uploaded_file($this->getCapaTemp(), "capas/".$this->getCapaNome());
                return $this->getCapaNome();
            }
            return null;
        }

        public function addRevistas () {
            $dados = array(
                "id_usuarios" => (int)$this->getId_user(),
                "revista" => $this->getRevista(),
                "titulo" => $this->getTitulo(),
                "autor" => $this->getAutor(),
                "editora" => $this->getEditora(),
                "numero" => (int)$this->getNumero(),
                "issn" => $this->getIssn(),
                "sinopse" => $this->getSinopse(),
                "opiniao" => $this->getOpiniao(),
                "ebook" => (int)$this->getEbook(),
                "arqempresta" => (int)$this->getArqEmprestar(),
                "arqmorto" => (int)$this->getArqMorto()
            );
            if ($this->getAno() !== null) {
                $dados["ano"] = $this->getAno();
            }
            if ($this->getCompra() !== null) {
                $dados["compra"] = $this->getCompra();
            }
            $capa = $this->instrucoes();
            if ($capa !== null) {
                $dados["capa"] = $capa;
            }
            $Conect = inserir("revistas", $dados);
            $this->setTbl($Conect->getResult());
        }

        public function altRevistas($cod) {
            $this->setCodigo((int)$cod);
            $dados = array(
                "revista" => $this->getRevista(),
                "titulo" => $this->getTitulo(),
                "autor" => $this->getAutor(),
                "editora" => $this->getEditora(),
                "numero" => (int)$this->getNumero(),
                "issn" => $this->getIssn(),
                "sinopse" => $this->getSinopse(),
                "opiniao" => $this->getOpiniao(),
                "ebook" => (int)$this->getEbook(),
                "arqempresta" => (int)$this->getArqEmprestar(),
                "arqmorto" => (int)$this->getArqMorto()
            );
            if ($this->getAno() !== null) {
                $dados["ano"] = $this->getAno();
            }
            if ($this->getCompra() !== null) {
                $dados["compra"] = $this->getCompra();
            }
            if ($this->getCapaSize() > 0 && capaValida($this->getCapaTemp(), $this->getCapaSize(), $this->getCapaExt())) {
                apagarCapa("revistas", "id_revistas", $this->getCodigo(), $this->getId_user());
                $dados["capa"] = $this->instrucoes();
            }
            $conect = atualizar("revistas", $dados, "id_revistas", $this->getCodigo(), $this->getId_user());
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
            $this->setId_usuario((int)$user);
            $this->setId_emprest(intOuNull($id_emprest));
            $this->setId_livros(intOuNull($li));
            $this->setId_revistas(intOuNull($re));
            $this->setNome((string)$nome);
            $this->setDt_saida(dataSql($sai));
            $this->setDt_entra(dataSql($entra));
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

        // Coluna e valor do periódico (livro ou revista) do empréstimo
        public function instrucao() {
            if ($this->getId_livros() != null) {
                return array("id_livros", (int)$this->getId_livros());
            }
            elseif ($this->getId_revistas() != null) {
                return array("id_revistas", (int)$this->getId_revistas());
            }
            return array("id_livros", 0); // nenhum periódico: não encontra nada
        }

        public function total() {
            $filtro = $this->instrucaoID();
            $this->setSql("SELECT COUNT(*) FROM emprestimo".$filtro[0]);
            $conect = new Conexao($this->getSql(), $filtro[1]);
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        public function listar() {
            $filtro = $this->instrucaoID();
            $this->setSql("SELECT * FROM emprestimo".$filtro[0]." ORDER BY dt_emprest DESC".$filtro[2]);
            $conect = new Conexao($this->getSql(), array_merge($filtro[1], $filtro[3]));
            $conect->conectar();
            $this->setTbl($conect->getResult());
        }

        // Monta o filtro WHERE (e o LIMIT) usado em total() e listar()
        public function instrucaoID() {
            $periodico = $this->instrucao();
            $where = " WHERE id_usuarios = ? AND ".$periodico[0]." = ?";
            $params = array((int)$this->getId_usuario(), $periodico[1]);

            if ($this->getId_emprest() != null) {
                $where .= " AND id_emprest = ?";
                $params[] = (int)$this->getId_emprest();
            }

            // aceita "10" ou "20,20" (início, quantidade)
            $limite = "";
            $paramsLimite = array();
            if ($this->getLimite() != null) {
                $partes = array_map(function ($n) { return max(0, (int)$n); }, explode(",", (string)$this->getLimite()));
                $limite = (count($partes) > 1) ? " LIMIT ?, ?" : " LIMIT ?";
                $paramsLimite = array_slice($partes, 0, 2);
            }
            return array($where, $params, $limite, $paramsLimite);
        }

        public function addEmprest() {
            if ($this->getDt_saida() == null || trim($this->getNome()) == "") {
                return "Informe o nome e a data de saída";
            }
            $periodico = $this->instrucao();
            $conect = inserir("emprestimo", array(
                "id_usuarios" => (int)$this->getId_usuario(),
                $periodico[0] => $periodico[1],
                "nome" => $this->getNome(),
                "dt_emprest" => $this->getDt_saida(),
                "dt_devol" => $this->getDt_entra() // null grava NULL
            ));
            $this->setTbl($conect->getResult());
            return "Empréstimo adicionado";
        }

        // Deixa o livro/revista "emprestado" enquanto houver empréstimo sem devolução
        // e devolve para a estante quando todos forem devolvidos (eBooks não mudam).
        public function sincronizarSituacao() {
            $periodico = $this->instrucao();
            $tabela = ($periodico[0] === "id_livros") ? "livros" : "revistas";
            $conta = new Conexao(
                "SELECT COUNT(*) FROM emprestimo WHERE id_usuarios = ? AND ".$periodico[0]." = ? AND dt_devol IS NULL",
                array((int)$this->getId_usuario(), $periodico[1])
            );
            $conta->conectar();
            $emAberto = ((int)mysqli_fetch_array($conta->getResult())[0] > 0);
            $atualiza = new Conexao(
                "UPDATE ".$tabela." SET arqempresta = ?".($emAberto ? ", arqmorto = 0" : "")." WHERE ".$periodico[0]." = ? AND id_usuarios = ? AND ebook = 0",
                array($emAberto ? 1 : 0, $periodico[1], (int)$this->getId_usuario())
            );
            $atualiza->conectar();
        }

        public function altEmprest() {
            if ($this->getId_emprest() == null) {
                return "Selecione um empréstimo da lista para alterar";
            }
            if ($this->getDt_saida() == null || trim($this->getNome()) == "") {
                return "Informe o nome e a data de saída";
            }
            $this->setSql("UPDATE emprestimo SET nome = ?, dt_emprest = ?, dt_devol = ? WHERE id_emprest = ? AND id_usuarios = ?");
            $conect = new Conexao($this->getSql(), array(
                $this->getNome(), $this->getDt_saida(), $this->getDt_entra(),
                (int)$this->getId_emprest(), (int)$this->getId_usuario()
            ));
            $conect->conectar();
            return "Empréstimo alterado";
        }

    }
?>