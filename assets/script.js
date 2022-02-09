window.onload = function(){
    if(document.getElementById("logout-link") != null){document.getElementById("logout-link").addEventListener("click", confirmeLogout, false);}
    if(document.querySelectorAll('.itemDelete') != null){
        document.querySelectorAll('.itemDelete').forEach((el) =>
            el.addEventListener('click', confirmeDelete, false)
        );
    }
    if(document.getElementById("formSearch") != null){document.getElementById("formSearch").addEventListener("reset", cancelSearch, false);} //Pesquisa de itens
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
            grandpa.style.display = "block";
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
            var father = el.parentNode;
            var grandpa = father.parentNode;
            grandpa.style.display = "block";
        });
    }

    //user is "finished typing," do something
    function doneTyping () {
        var text = myInput.value;
        if(text.length > 0){
            var verif = false;
            var list = document.querySelectorAll( '.inputSearch' );
            Array.prototype.forEach.call(list, function (el) {
                if(el.innerHTML.toLowerCase().includes(text.toLowerCase()) == false){
                    var father = el.parentNode;
                    var grandpa = father.parentNode;
                    grandpa.style.display = "none";
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
