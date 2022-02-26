window.onload = function(){
    var projetoId = document.getElementById("hiddenWithId").value;
    document.getElementById("task_idProjeto").value = projetoId;
    document.getElementById("img_choose_id").value = projetoId;
    document.querySelectorAll('#task_type_edit_idProjet').forEach((el) =>
        el.value = projetoId
    );
    document.querySelectorAll('#newClassInput').forEach((el) =>
        el.style.display = "none"
    );
    document.querySelectorAll('#task_type_edit_nomeClass').forEach((el) =>
        el.value = "nulo"
    );
    document.querySelectorAll('#task_nomeClass').forEach((el) =>
        el.value = "nulo"
    );
    document.querySelectorAll('#btnNewClass').forEach((el) =>
        el.addEventListener("click", showNewClass, false)
    );
    document.querySelectorAll('.btnEditTask').forEach((el) =>
        el.addEventListener("click", function(){
            editarTask(el.id)
        }, false)
    );
    document.querySelectorAll('.editDiv').forEach((el) =>
        el.style.display = "none"
    );
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

function showNewClass(){
    var opt = document.createElement('option');
    opt.selected = "selected";
    opt.value = "false";
    document.querySelectorAll('#task_class').forEach((el) =>
        el.appendChild(opt)
    );
    document.querySelectorAll('#task_type_edit_class').forEach((el) =>
        el.appendChild(opt)
    );

    document.querySelectorAll('#task_nomeClass').forEach((el) =>
        el.value = ""
    );
    document.querySelectorAll('#task_type_edit_nomeClass').forEach((el) =>
        el.value = ""
    );
    document.querySelectorAll('#classificacaoInput').forEach((el) =>
        el.style.display = "none"
    );
    document.querySelectorAll('#btnNewClass').forEach((el) =>
        el.style.display = "none"
    );
    document.querySelectorAll('#newClassInput').forEach((el) =>
        el.style.display = "block"
    );
}

function editarTask(idTask){
    document.querySelectorAll('.readDiv'+idTask).forEach((el) =>
        el.style.display = "none"
    );
    document.querySelectorAll('.edit'+idTask).forEach((el) =>
        el.style.display = "block"
    );
    var select = '#task'+idTask+' form #task_type_edit_idTask';
    document.querySelectorAll(select).forEach((el) =>
        el.value = idTask
    );
}