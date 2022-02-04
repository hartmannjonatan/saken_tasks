window.onload = function(){
    document.getElementById("logout-link").addEventListener("click", confirmeLogout, false);
}

function confirmeLogout(evt) {
    var res = confirm("Você realmente deseja sair?");

    if(res != true){
        evt.preventDefault();
    }
}