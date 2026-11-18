<?php
    require_once ('php/funcao.php');
    require_once ('php/usuarios.php');

    $mensagemLogin = "";
    if (isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'entrar') {
        $usuario = new Logar($_POST['usuarioLog'] ?? '', $_POST['senhaLog'] ?? '');
        $mensagemLogin = $usuario->validar(); // redireciona para inicio.php se estiver correto
    }

    abrirPagina('Entrar', 'entrar', false);
    boasVindas('Bem-vindo à Estante Virtual');
?>
        <section class="painel acesso">
            <h2>Entrar</h2>
            <form action="login.php?acao=entrar" method="post" id="idFormLog">
                <div class="campos">
                    <div class="campo inteiro">
                        <label for="idUserLog">Usuário</label>
                        <input type="text" name="usuarioLog" id="idUserLog" autocomplete="username" value="<?=h($_POST['usuarioLog'] ?? '')?>">
                    </div>
                    <div class="campo inteiro">
                        <label for="idSenhaLog">Senha</label>
                        <input type="password" name="senhaLog" id="idSenhaLog" autocomplete="current-password">
                    </div>
                </div>
                <p id="idErro" class="mensagem erro" style="margin-top:16px"><?=h($mensagemLogin)?></p>
                <div class="botoes" style="grid-template-columns:1fr">
                    <button type="submit" class="botao botao-primario" id="idLogar" onclick="logar()">Entrar</button>
                </div>
            </form>
            <p class="alternar">Ainda não tem conta? <a href="cadastrar.php">Cadastre-se</a></p>
        </section>
<?php
    fecharPagina(array('js/cadlog.js'));
?>
