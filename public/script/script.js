"use strict";
function multiplication(a, b) {
    return a * b;
}
var appEl = document.getElementById("app");

var chosen = 6;
for (var i = 0; i < 10; i++) {
    var product = multiplication(chosen, i);
    appEl.innerHTML += "\n    <span>" + chosen + " x " + i + " = " + product + "</span><br>\n    ";
}

class Websocket {
    constructor(url) {
        this.socket = new WebSocket(url);
    }

    open = async () => new Promise((resolve) => {
        this.socket.addEventListener("open", (event) => {
            resolve(event);
        }, {once: true});
    });

    send = async (data) => {
        this.socket.send(data);
    };

    receive = async () => new Promise( resolve => {
        this.socket.addEventListener("message", (event) =>{
            resolve(event);
        }, {once: true});
    });
}

