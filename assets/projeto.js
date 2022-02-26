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

    if(document.getElementById("formSearch") != null){document.getElementById("formSearch").addEventListener("reset", cancelSearch, false);} //Pesquisa de itens
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

if(document.getElementById('search') != null){ //Funções para pesquisar itens com javascript
    //setup before functions
    let typingTimer;                //timer identifier
    let doneTypingInterval = 500;  //time in ms (5 seconds)
    let myInput = document.getElementById('search');

    //on keyup, start the countdown
    myInput.addEventListener('keyup', () => {
        var list = document.querySelectorAll( '.inputSearch' );
        Array.prototype.forEach.call(list, function (el) {
            var father = el.parentNode;
            var grandpa = father.parentNode;
            var grandpafather = grandpa.parentNode;
            var grandpagrandpa = grandpafather.parentNode;
            grandpagrandpa.style.display = "block";
        });
        document.getElementById("resetSearch").style.visibility = "visible";
        clearTimeout(typingTimer);
        if (myInput.value) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });

    function cancelSearch() {
        document.getElementById("hasNot").style.visibility = "hidden";
        var list = document.querySelectorAll( '.inputSearch' );
        Array.prototype.forEach.call(list, function (el) {
            el.style.display = "block";
            var father = el.parentNode;
            father.style.display = "block";
            var grandpa = father.parentNode;
            grandpa.style.display = "block";
            var grandpafather = grandpa.parentNode;
            grandpafather.style.display = "block";
            var grandpagrandpa = grandpafather.parentNode;
            grandpagrandpa.style.display = "block";
        });
    }

    //user is "finished typing," do something
    function doneTyping () {
        document.getElementById('detail-tasks-checked').open = "true";
        var text = myInput.value;
        if(text.length > 0){
            var verif = false;
            var list = document.querySelectorAll( '.inputSearch' );
            Array.prototype.forEach.call(list, function (el) {
                if(el.innerHTML.toLowerCase().includes(text.toLowerCase()) == false){
                    var father = el.parentNode;
                    var grandpa = father.parentNode;
                    var grandpafather = grandpa.parentNode;
                    var grandpagrandpa = grandpafather.parentNode;
                    grandpagrandpa.style.display = "none";
                } else{
                    verif = true;
                }
            });

            if(verif == false){
                document.getElementById("hasNot").style.visibility = "visible";
            }
        }
    }
}