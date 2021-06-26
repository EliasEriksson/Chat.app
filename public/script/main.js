"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var mod_ts_1 = require("https://deno.land/x/websocket@v0.1.1/mod.ts");
// echo server demo
// let socket: WebSocketServer = new WebSocketServer(6969);
//
// socket.on("connection", function (ws: WebSocketClient){
//     console.log(ws);
//     ws.on("message", function (message: string) {
//         console.log(message);
//         ws.send(message);
//     });
// });
/**
 *  this class will handle incoming connections to the chat server
 */
var Websockets = /** @class */ (function () {
    function Websockets(port) {
        this.socket = new mod_ts_1.WebSocketServer(port);
        this.connections = {};
        this.socket.on("connection", function (ws) {
        });
    }
    return Websockets;
}());
