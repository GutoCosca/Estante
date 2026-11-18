// Mostra a data atual no menu (versão curta em telas até 1024px)
function data(tela) {
    let semana, mes;
    if (tela.matches) {
        semana = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"];
        mes = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
    }
    else {
        semana = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
        mes = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    }
    const hoje = new Date();
    const campo = document.querySelector('#idData');
    if (campo) {
        campo.innerText = semana[hoje.getDay()] + " - " + hoje.getDate() + " de " + mes[hoje.getMonth()] + " de " + hoje.getFullYear();
    }
}

const tela = window.matchMedia("(max-width: 1024px)");
data(tela);
tela.addEventListener("change", function() {
    data(tela);
});
