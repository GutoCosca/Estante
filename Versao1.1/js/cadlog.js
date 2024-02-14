const formularioCad = document.querySelector('#idFormCad');
const usuarioCad = document.querySelector('#idUserCad');
const nomeCad = document.querySelector('#idNomeCad');
const emailCad = document.querySelector('#idEmailCad');
const senha1Cad = document.querySelector('#idSenha1Cad');
const senha2Cad = document.querySelector('#idSenha2Cad');
const larguraCad = document.querySelector('#idLargCad');
const maiusculaCad = document.querySelector('#idMaiusCad');
const minusculaCad = document.querySelector('#idMinusCad');
const numeroCad = document.querySelector('#idNumCad');
const especialCad = document.querySelector('#idEspCad');
const mensagem = formularioCad.querySelectorAll('div');
const lista = document.querySelector('ul');
const regra = lista.querySelectorAll('.regra');
const formulario = document.querySelectorAll('input');
const formularioLog = document.querySelector('#idFormLog');
const usuarioLog = document.querySelector('#idUserLog');
const senhaLog = document.querySelector('#idSenhaLog');
const site = document.location.search;

// Informa erros após a digitação no input
function testar(caso) {
    
    switch (caso){
        case 0:
            validaNome(usuarioCad, 'controleCad erro', 'controleCad', "Usuário inválido!!", "(Mínimo 03, máximo 12 caracteres");
            document.querySelector('#idUsuario').innerText = "";
            break;
        case 1:
            validaNome(nomeCad, 'controleCad erro', 'controleCad', "Nome inválido!!", "(Mínimo 03, máximo 12 caracteres");
            break;
        case 2:
            validaEmail(emailCad,'controleCad erro', 'controleCad', "Email inválido!!", 'Deve conter "@" e "." min. 2 máx. 4 letras');
            break;
        case 3:
            validaSenha1(senha1Cad,'controleCad erro', 'controleCad', "Senha inválida!!", "Deve conter:");
            break;
        case 4:
            validaSenha2(senha1Cad, senha2Cad,'controleCad erro', 'controleCad', "Senha não confere!!", 'Deve ser igual a senha criada.');
            break;
    }
}

// Testa o formulário cadastro
function cadastrar() {
    formularioCad.onsubmit = eventoCad => {
        if (!testeCad()) {
            eventoCad.preventDefault();
        }
    }
}

// Testa todos os formularios digitados na tela cadastro
function testeCad() {
    statusCad = false;
    statusUsuarioCad = validaNome(usuarioCad, 'controleCad erro', 'controleCad', "Usuário inválido!!", "(Mínimo 03, máximo 12 caracteres");
    statusNomeCad = validaNome(nomeCad, 'controleCad erro', 'controleCad', "Nome inválido!!", "(Mínimo 03, máximo 12 caracteres");
    statusEmailCad = validaEmail(emailCad,'controleCad erro', 'controleCad', "Email inválido!!", 'Deve conter "@" e "." min. 2 máx. 4 letras');
    statusSenha1Cad = validaSenha1(senha1Cad,'controleCad erro', 'controleCad', "Senha inválida!!", 'Deve conter:');
    statusSenha2Cad = validaSenha2(senha1Cad, senha2Cad,'controleCad erro', 'controleCad', "Senha não confere!!", 'Deve ser igual a senha criada.');

    if (!statusUsuarioCad || !statusNomeCad || !statusEmailCad || !statusSenha1Cad || !statusSenha2Cad) {
        statusCad = false;
    }
    else {
        statusCad = true;
    }

    return statusCad;
}

//Testa o formulário usuário e nome , se vazio, se menor que 03 e se maior que 12 caracteres
function validaNome(nome, classe1, classe2, mensagem1, mensagem2) {
    
    if (!nome.value.trim() || nome.value.trim().length < 3 || nome.value.trim().length > 12 ) {
        statusValNome = erro(nome, classe1, mensagem1, mensagem2);
    }
    else {
        statusValNome = sucesso(nome, classe2);
    }

    return statusValNome;
}

// Testa o formulário email se contém @ e os 03 últimos digitos são letras
function validaEmail(email, classe1, classe2, mensagem1, mensagem2) {
    const emailModelo = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/g;

    if (!email.value.trim() || !email.value.trim().match(emailModelo)) {
        statusValEmail = erro(email, classe1, mensagem1, mensagem2);
    }
    else {
        statusValEmail = sucesso(email, classe2);
    }

    return statusValEmail;
}

// Testa a senha se contem letras maiúsculas, minúsculas, numeros, caracteres especiais, sem maior que 08 caracteres e menor que 12 caracteres
function validaSenha1(senha1, classe1, classe2, mensagem1, mensagem2, largura, maiuscula, minuscula, numero, especial) {
    const valida = senha1.value.trim();
    if (valida.length < 8 || valida.length > 12) {
        statusValLargura = false;
    }
    else {
        statusValLargura = true;
    }

    if (!valida.match(/[A-Z]/g)) {
        statusValMaiuscula = false;
    }
    else{
        statusValMaiuscula = true;
    }

    if (!valida.match(/[a-z]/g)) {
        statusValMinuscula = false;
    }
    else {
        statusValMinuscula = true;
    }

    if (!valida.match(/[0-9]/g)) {
        statusNumero = false;
    }
    else {
        statusNumero = true;
    }

    if (!valida.match(/[\W|_]/g)) {
        statusEspecial = false;
    }
    else {
        statusEspecial = true;
    }

    if (!statusValLargura || !statusValMaiuscula || !statusValMinuscula || !statusNumero || !statusEspecial) {
        statusValSenha1 = erro(senha1, classe1, mensagem1, mensagem2);
    }
    else {
        statusValSenha1 = sucesso(senha1, classe2);
    }

    return statusValSenha1;
}
// Risca os itens validados da senha
function marcaSenha1(largura, maiuscula, minuscula, numero, especial) {
    const valida = senha1Cad.value.trim();
    if (valida.length < 8 || valida.length > 12) {
        statusValLargura = negativo(larguraCad);
    }
    else {
        statusValLargura = positivo(larguraCad);
    }

    if (!valida.match(/[A-Z]/g)) {
        statusValMaiuscula = negativo(maiusculaCad);
    }
    else{
        statusValMaiuscula = positivo(maiusculaCad);
    }

    if (!valida.match(/[a-z]/g)) {
        statusValMinuscula = negativo(minusculaCad);
    }
    else {
        statusValMinuscula = positivo(minusculaCad);
    }

    if (!valida.match(/[0-9]/g)) {
        statusNumero = negativo(numeroCad);
    }
    else {
        statusNumero = positivo(numeroCad);
    }

    if (!valida.match(/[\W|_]/g)) {
        statusEspecial = negativo(especialCad);
    }
    else {
        statusEspecial = positivo(especialCad);
    }
}

function validaSenha2(senha1, senha2, classe1, classe2, mensagem1, mensagem2,) {
    if (!(senha1.value.trim() === senha2.value.trim()) || !senha2.value.trim()) {
        statusValSenha2 = erro(senha2, classe1, mensagem1, mensagem2);
    }
    else {
        statusValSenha2 = sucesso(senha2, classe2);
    }

    return statusValSenha2;
}

function negativo(estilo) {
    estilo.style.textDecoration = 'none';
    estilo.style.color = '#ff0000';
    return false;
}

function positivo(estilo) {
    estilo.style.textDecoration = 'line-through';
    estilo.style.color = '#000000';
    return true;
}

function erro(nome, classe, mensagem1, mensagem2) {
    const formControle = nome.parentElement;
    const small1 = formControle.querySelector('.atencao');
    const small2 = formControle.querySelector('.regra');
    formControle.className = classe
    small1.innerText = mensagem1;
    small2.innerText = mensagem2;
    return false;
}

function sucesso(nome, classe) {
    const formControle = nome.parentElement;
    formControle.className = classe;
    return true;
}

function logar() {
    formularioLog.onsubmit = eventoLog => {
        if (!testeLog()) {
            eventoLog.preventDefault();
        }
    }
}

function testeLog() {
    if (!usuarioLog.value.trim() || !senhaLog.value.trim()) {
        const smalllog = document.querySelector('#erro');
        smalllog.style.visibility = 'visible';
        smalllog.style.color = '#ff0000'
        smalllog.style.textAlign ='center'
        smalllog.innerText = "Os campos não podem ficar em branco!!!";
        statusLog = false ;
    }
    else {
        statusLog = true;
    }

    return statusLog;
}

function acaoCadastro() {
    if (site === "?acao=cadastrar") {
        document.querySelector('.logar').style.visibility = 'visible';
        document.querySelector('.cadastro').style.visibility = 'hidden';
        //Remove as mensagens de erro
        mensagem.forEach(elementoMsn => {
            elementoMsn.className = 'controleCad';
        })
        //Retorna o texto da regra da senha para preto
        regra.forEach(elementoRegra => {
            elementoRegra.style.color = '#000000'; 
            elementoRegra.style.textDecoration = 'none';
        })
        //Limpa os formularios
        formulario.forEach(elementoForm => {
            elementoForm.value="";
        })
    }
}