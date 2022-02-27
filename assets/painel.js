import interact from 'interactjs';

let coord = new Map();
let id = -1;
document.querySelectorAll('.grid-snap').forEach((element) => {
    id++;
    element.id = id;
    var x = 0; var y = 0;
    interact(element)
    .draggable({
        modifiers: [
        interact.modifiers.snap({
            targets: [
            interact.snappers.grid({ x: 30, y: 30 })
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
        coord.set(id, element.getBoundingClientRect());
        console.log(coord.get(id));
        var nomeKeyLocal = "el"+element.id;
        localStorage.setItem(nomeKeyLocal, JSON.stringify(element.getBoundingClientRect()));
        console.log(nomeKeyLocal);
        event.target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'
    })
});

window.onload = function(){
    var id = -1;
    document.querySelectorAll('.grid-snap').forEach((element) => {
        id++;
        var nomeKeyLocal = "el"+id;
        console.log(nomeKeyLocal);
        var coordLocal = localStorage.getItem(nomeKeyLocal);
        coordLocal = JSON.parse(coordLocal);
        console.log(coordLocal);
        if(coordLocal !== null){
            var top = coordLocal.top;
            var left = coordLocal.left;
            element.style.top = top+"px";
            element.style.left = left+"px";
            console.log("top "+top+" left: "+left);
        } else{
            element.style.top = "10px";
            element.style.left = "10px";
        }
    });
}