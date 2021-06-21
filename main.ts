import { WebSocketServer, WebSocketClient } from "https://deno.land/x/websocket@v0.1.1/mod.ts";

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
class Websockets {
    private socket: WebSocketServer;
    private connections: Record<string, WebSocketClient>;

    constructor(port: number) {
        this.socket = new WebSocketServer(port);
        this.connections = {}


        this.socket.on("connection", function (ws: WebSocketClient) {

        });
    }
}