const site = window.location.search;
function acaoCadastro() {
    if (site === "?acao=cadastrar") {
        document.querySelector('.logar').style.visibility = 'hidden';
        document.querySelector('.cadastro').style.visibility = 'visible';
        //Remove as mensagens de erro
        /*mensagem.forEach(elementoMsn => {
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
        })*/
    }
}
console.log (location.href);
console.log (site);