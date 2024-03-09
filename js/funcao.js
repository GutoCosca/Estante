// const site = window.location.search;
// function acaoCadastro() {
//     if (site === "?acao=cadastrar") {
//         document.querySelector('.logar').style.visibility = 'hidden';
//         document.querySelector('.cadastro').style.visibility = 'visible';
//         //Remove as mensagens de erro
//         /*mensagem.forEach(elementoMsn => {
//             elementoMsn.className = 'controleCad';
//         })
//         //Retorna o texto da regra da senha para preto
//         regra.forEach(elementoRegra => {
//             elementoRegra.style.color = '#000000'; 
//             elementoRegra.style.textDecoration = 'none';
//         })
//         //Limpa os formularios
//         formulario.forEach(elementoForm => {
//             elementoForm.value="";
//         })*/
//     }
// }
// console.log (location.href);
// console.log (site);
function data(){
    semana = new Array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
    mes = new Array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    hoje = new Date;
    document.querySelector('#idData').innerText = semana[hoje.getDay()] + " - " + hoje.getDate() + " de " + mes[hoje.getMonth()] + " de " + hoje.getFullYear();
}