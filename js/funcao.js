const site = window.location.search;
function acaoCadastro() {
    if (site === "?acao=cadastrar") {
        document.querySelector('.logar').style.visibility = 'hidden';
        document.querySelector('.cadastro').style.visibility = 'visible';
    }
}
console.log (location.href);
console.log (site);