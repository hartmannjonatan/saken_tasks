window.onload = function(){
    const projetoId = document.getElementById("hiddenWithId").value;
    document.getElementById("task_idProjeto").value = projetoId;
    document.getElementById("newClassInput").style.display = "none";
    document.getElementById("task_nomeClass").value = "nulo";
    if(document.getElementById("btnNewClass") !=  null){document.getElementById("btnNewClass").addEventListener("click", showNewClass, false);}
}

function showNewClass(){
    var select = document.getElementById("task_class");
    var opt = document.createElement('option');
    opt.selected = "selected";
    opt.value = "false";
    select.appendChild(opt);

    document.getElementById("task_nomeClass").value = "";
    document.getElementById("classificacaoInput").style.display = "none";
    document.getElementById("btnNewClass").style.display = "none";
    document.getElementById("newClassInput").style.display = "block";
}