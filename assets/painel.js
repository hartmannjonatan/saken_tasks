import interact from 'interactjs';

let idPainel;
document.querySelectorAll('.painel').forEach((element) => {
    element.style.display = "none";
    idPainel = element.id;
});

let coord = new Map();
let id = 0;
document.querySelectorAll('.grid-snap').forEach((element) => {
    draggable(element);
});

function draggable(element){
    id++;
    element.id = id;
    var x = 0; var y = 0;
    interact(element)
    .draggable({
        modifiers: [
        interact.modifiers.snap({
            targets: [
            interact.snappers.grid({ x: 1, y: 1 })
            ],
            range: Infinity,
            relativePoints: [ { x: 0, y: 0 } ]
        }),
        interact.modifiers.restrict({
            restriction: element.parentNode,
            elementRect: { top: 0, left: 0, bottom: 1, right: 1 },
            endOnly: true
        })
        ],
        inertia: true
    })
    .on('dragmove', function (event) {
        x += event.dx
        y += event.dy
        var coordXY = new Object();
        coordXY.x = x;
        coordXY.y = y;
        coord.set(element.id, element.getBoundingClientRect());
        console.log(coord.get(element.id));
        var nomeKeyLocal = idPainel+"el"+element.id;;
        localStorage.setItem(nomeKeyLocal, JSON.stringify(coordXY));
        console.log(nomeKeyLocal);
        event.target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'
    })
}

let save = false;
let notes = Array();

window.onload = function(){
    var id = 0;
    document.querySelectorAll('.grid-snap').forEach((element) => {
        id++;
        var nomeKeyLocal = idPainel+"el"+id;
        console.log(nomeKeyLocal);
        var coordLocal = localStorage.getItem(nomeKeyLocal);
        coordLocal = JSON.parse(coordLocal);
        console.log(coordLocal);
        if(coordLocal !== null){
            var dx = coordLocal.x;
            var dy = coordLocal.y;
            element.style.transform = 'translate(' + dx + 'px, ' + dy + 'px)'
        } else{
            var d = id*80 + 5;
            element.style.top = d+"px";
            element.style.left = "10px";
        }
    });

    window.addEventListener('beforeunload', function (e) {
        if(document.getElementById("btnSave").style.display == "block"){
            if(save == false){
                e.returnValue = '';
            }
        }
    });

    document.querySelectorAll('.grid-snap').forEach((element) => {
        var cardbody = element.children[0];
        var cardtitle = cardbody.children[0];
        var title = cardtitle.children[0];
        var content = cardbody.children[2];

        var noteExistent = new Object();
        noteExistent.id = element.id;
        noteExistent.color = element.classList.item(2);
        noteExistent.title = title.innerText;
        noteExistent.content = content.innerText;
        noteExistent.created = false;
        noteExistent.edited = false;
        noteExistent.action = false;
        noteExistent.deleting = false;

        notes.push(noteExistent);
    });

    document.querySelectorAll('.itemDelete').forEach((element) => {
        element.addEventListener("click", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;
            var bisv = vo.parentNode;
            var tat = bisv.parentNode;

            var res = confirm("Você realmente deseja excluir essa nota?");
            if(res == true){
                deleteNote(tat);
                saveDisplay();
            }
        }, false);
    });

    document.querySelectorAll('.title-note').forEach((element) => {
        element.addEventListener("input", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;
            var bisv = vo.parentNode;

            createTitle(element.innerText, bisv.id);
            saveDisplay();
            console.log(notes);
        }, false);
    });

    document.querySelectorAll('.content-note').forEach((element) => {
        element.addEventListener("input", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;

            createContent(element.innerText, vo.id);
            saveDisplay();
        }, false);
    });

    document.getElementById("newNote").addEventListener("click", newNote, false);
    document.getElementById("formNote").style.display = "none";
    document.getElementById("btnSave").style.display = "none";
    document.getElementById("btnSave").addEventListener("click", function() {
        submitValues();
    });
}

function deleteNote(NoteDelete) {
    notes.forEach(element => {
        if(element.id == NoteDelete.id){
            element.deleting = true;
            element.action = true;
            NoteDelete.style.visibility = "hidden";
        }
    });
}

function createTitle(title, idNoteEdit){
    notes.forEach(element => {
        if(element.id == idNoteEdit){
            element.title = title;
            if(element.created == false){
                element.edited = true;
                element.action = true;
            }
        }
    });
}

function createContent(content, idNoteEdit){
    notes.forEach(element => {
        if(element.id == idNoteEdit){
            element.content = content;
            if(element.created == false){
                element.edited = true;
                element.action = true;
            }
        }
    });
}

function submitValues() {
    var form = document.getElementById("formNote");

    var inpIdPainel = document.createElement("input");
    inpIdPainel.type = "hidden";
    inpIdPainel.name = "idPainel";
    inpIdPainel.value = idPainel;
    form.appendChild(inpIdPainel);

    var inpNotes = document.createElement("input");
    inpNotes.type = "hidden";
    inpNotes.name = "notes";
    inpNotes.value = JSON.stringify(notes);
    form.appendChild(inpNotes);
    
    save = true;

    document.getElementById("formNote").submit();
}

function saveDisplay() {
    document.getElementById("btnSave").style.display = "block";
}

function newNote(){
    var painel = document.getElementById("painel");

    var note = document.createElement("div");
    var bgNote = Array();
    bgNote.push("bg-primary", "bg-secondary", "bg-success", "bg-danger", "bg-warning", "bg-info", "bg-light", "bg-dark");
    var bgSort = bgNote[Math.floor(Math.random() * bgNote.length)];
    var classList = " grid-snap position-absolute "+bgSort;
    note.className += classList;

    var cardBody = "<div class='card-body'><div class='card-title d-flex justify-content-between lato h4'><div contentEditable='true' class='title-note'>Título</div><div><a class='mx-2 itemDelete' href='#'><?xml version='1.0' encoding='iso-8859-1'?><!-- Generator: Adobe Illustrator 18.1.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'viewBox='0 0 174.239 174.239' style='enable-background:new 0 0 174.239 174.239; width: 30px;' xml:space='preserve'><g><path d='M138.759,64.462H36.001c-0.647,0-1.245,0.099-1.842,0.348c-2.091,0.747-3.634,2.738-3.634,5.078l-0.1,14.488l-0.448,84.436c0,2.987,2.439,5.427,5.426,5.427h102.757c3.037,0,5.526-2.439,5.526-5.427l0.498-98.924C144.235,66.902,141.796,64.462,138.759,64.462z M54.572,89.007h11.451v60.689H54.572V89.007z M81.356,89.007h11.451v60.689H81.356V89.007z M119.641,149.695H108.19V89.007h11.451V149.695z'/><path d='M138.795,44.875c3.007,0,5.467-2.46,5.467-5.467V24.668c0-3.007-2.46-5.467-5.467-5.467h-25.133c-3.007,0-5.467-2.46-5.467-5.467V5.467c0-3.007-2.46-5.467-5.467-5.467H72.168c-3.007,0-5.476,2.46-5.487,5.467l-0.031,8.268c-0.011,3.007-2.481,5.467-5.487,5.467H36.029c-3.007,0-5.467,2.46-5.467,5.467v14.741c0,3.007,2.46,5.467,5.467,5.467H138.795z'/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a></div></div><hr><div class='card-text h6 content-note' contentEditable='true'>Conteúdo...</div></div>";

    note.innerHTML = cardBody;

    painel.appendChild(note);
    draggable(note);

    var noteCreated = new Object();
    noteCreated.id = note.id;
    noteCreated.color = bgSort;
    noteCreated.title = "Título";
    noteCreated.content = "Conteúdo...";
    noteCreated.created = true;
    noteCreated.edited = false;
    noteCreated.action = true;
    noteCreated.deleting = false;

    notes.push(noteCreated);

    saveDisplay();

    //OUVINTE DE EVENTO PARA A NOTA CRIADA:
    document.querySelectorAll('.title-note').forEach((element) => {
        element.addEventListener("input", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;
            var bisv = vo.parentNode;

            createTitle(element.innerText, bisv.id);
            saveDisplay();
            console.log(notes);
        }, false);
    });

    document.querySelectorAll('.content-note').forEach((element) => {
        element.addEventListener("input", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;

            createContent(element.innerText, vo.id);
            saveDisplay();
        }, false);
    });

    document.querySelectorAll('.itemDelete').forEach((element) => {
        element.addEventListener("click", function() {
            var pai = element.parentNode;
            var vo = pai.parentNode;
            var bisv = vo.parentNode;
            var tat = bisv.parentNode;

            var res = confirm("Você realmente deseja excluir essa nota?");
            if(res == true){
                deleteNote(tat);
                saveDisplay();
            }
        }, false);
    });
}