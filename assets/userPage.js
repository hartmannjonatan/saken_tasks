window.onload = function(){
    if(document.querySelectorAll('.itemDelete') != null){
        document.querySelectorAll('.itemDelete').forEach((el) =>
            el.addEventListener('click', confirmeDelete, false)
        );
    }

    document.querySelectorAll(".checkbox").forEach((checkbox) =>
        checkbox.addEventListener( 'change', function() {
            setTimeout(() => {  checkTask(this.id); }, 700);
        })
    );
}
function checkTask(id){
    var idForm = "check"+id.replace("cbx", "");
    document.getElementById(idForm).submit();
}

function confirmeDelete(evt) {
    var res = confirm("Você realmente deseja excluir esse item?");

    if(res != true){
        evt.preventDefault();
    }
}
