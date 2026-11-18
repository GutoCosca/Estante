<?php
    require_once ('php/funcao.php');
    require_once ('php/usuarios.php');

    $mensagem = "";
    $sucesso = false;
    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'cadastrar') {
        $usuario = new Cadastrar(
            $_POST['usuarioCad'] ?? '',
            $_POST['nomeCad'] ?? '',
            $_POST['emailCad'] ?? '',
            $_POST['senhaCad'] ?? ''
        );
        $mensagem = $usuario->cadastro();
        $sucesso = ($mensagem === "Usuário cadastrado com sucesso");
    }
    // mantém o que foi digitado se o cadastro falhar
    function valorCad($campo, $sucesso) {
        return $sucesso ? '' : h($_POST[$campo] ?? '');
    }

    abrirPagina('Cadastrar', 'cadastrar', false);
    boasVindas('Bem-vindo à Estante Virtual');
?>
        <section class="painel acesso">
            <h2>Cadastrar</h2>
            <form action="cadastrar.php?acao=cadastrar" method="post" id="idFormCad">
                <div class="campos">
                    <div class="controleCad campo">
                        <label for="idUserCad">Usuário</label>
                        <input type="text" name="usuarioCad" id="idUserCad" autocomplete="username" onblur="testar(0)" value="<?=valorCad('usuarioCad', $sucesso)?>">
                        <small class="atencao">Mensagem de erro</small>
                        <small class="regra">Mensagem de regra</small>
                    </div>
                    <div class="controleCad campo">
                        <label for="idNomeCad">Primeiro nome</label>
                        <input type="text" name="nomeCad" id="idNomeCad" autocomplete="given-name" onblur="testar(1)" value="<?=valorCad('nomeCad', $sucesso)?>">
                        <small class="atencao">Mensagem de erro</small>
                        <small class="regra">Mensagem de regra</small>
                    </div>
                    <div class="controleCad campo inteiro">
                        <label for="idEmailCad">Email</label>
                        <input type="text" name="emailCad" id="idEmailCad" autocomplete="email" onblur="testar(2)" value="<?=valorCad('emailCad', $sucesso)?>">
                        <small class="atencao">Mensagem de erro</small>
                        <small class="regra">Mensagem de regra</small>
                    </div>
                    <div class="controleCad campo">
                        <label for="idSenha1Cad">Senha</label>
                        <input type="password" name="senhaCad" id="idSenha1Cad" autocomplete="new-password" onblur="testar(3)" onkeyup="marcaSenha1()">
                        <small class="atencao">Mensagem de erro</small>
                        <small class="regra">Mensagem de regra</small>
                    </div>
                    <div class="controleCad campo">
                        <label for="idSenha2Cad">Confirme a senha</label>
                        <input type="password" name="senha2" id="idSenha2Cad" autocomplete="new-password" onblur="testar(4)">
                        <small class="atencao">Mensagem de erro</small>
                        <small class="regra">Mensagem de regra</small>
                    </div>
                    <div class="campo inteiro">
                        <span class="rotulo">A senha deve:</span>
                        <ul class="regras">
                            <li class="regra" id="idLargCad">Ter no mínimo 08 e no máximo 12 caracteres;</li>
                            <li class="regra" id="idMaiusCad">Ter letras maiúsculas;</li>
                            <li class="regra" id="idMinusCad">Ter letras minúsculas;</li>
                            <li class="regra" id="idNumCad">Ter números;</li>
                            <li class="regra" id="idEspCad">Ter caracteres especiais.</li>
                        </ul>
                    </div>
                </div>
                <p id="idErro" class="mensagem<?=$sucesso ? '' : ' erro'?>" style="margin-top:16px"><?=h($mensagem)?><?php if ($sucesso) { ?> <a href="login.php">Entrar agora</a><?php } ?></p>
                <div class="botoes">
                    <button type="submit" class="botao botao-primario" id="idCadastra" onclick="cadastrar()">Cadastrar</button>
                    <button type="reset" class="botao botao-secundario">Limpar</button>
                </div>
            </form>
            <p class="alternar">Já tem conta? <a href="login.php">Entrar</a></p>
        </section>
<?php
    fecharPagina(array('js/cadlog.js'));
?>
