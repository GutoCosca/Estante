const site = window.location.search;
function acaoCadastro() {
    if (site === "?acao=cadastrar") {
        document.querySelector('.logar').style.visibility = 'hidden';
        document.querySelector('.cadastro').style.visibility = 'visible';
    }
}
console.log (location.href);
console.log (site);


function data(tela) {
    if (tela.matches) {
        semana = new Array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab");
        mes = new Array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");
    }
    else {
        semana = new Array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
        mes = new Array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    }
    hoje = new Date;
    document.querySelector('#idData').innerText = semana[hoje.getDay()] + " - " + hoje.getDate() + " de " + mes[hoje.getMonth()] + " de " + hoje.getFullYear();
}

var tela = window.matchMedia("(max-width: 1024px)");
data(tela);
tela.addEventListener("change", function() {
    data(tela);
})