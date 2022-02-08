window.onload = function(){
    document.getElementById("logout-link").addEventListener("click", confirmeLogout, false);
    document.querySelectorAll('.itemDelete').forEach((el) =>
        el.addEventListener('click', confirmeDelete, false)
    );
}

function confirmeLogout(evt) {
    var res = confirm("Você realmente deseja sair?");

    if(res != true){
        evt.preventDefault();
    }
}

function confirmeDelete(evt) {
    var res = confirm("Você realmente deseja excluir esse item?");

    if(res != true){
        evt.preventDefault();
    }
}