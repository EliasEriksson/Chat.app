import {
    WebSocketClient, WebSocketServer
} from "https://deno.land/x/websocket@v0.1.1/mod.ts";

import {
    Client
} from "./client.ts";



export class Server {
    private socket: WebSocketServer;
    private clients: Set<Client>;

    constructor(port: number) {
        this.clients = new Set<Client>();
        this.socket = new WebSocketServer(port);
        this.socket.addListener("connection", this.handle_connection);
        this.socket.addListener("close", this.close_connection);
        console.log("waiting for connections...")
    }

    async handle_connection(ws: WebSocketClient) {
        console.log("somone connected.");
        let client = new Client(ws);
        let data;



    }

    async close_connection(client: Client, code: number) {
        await client.close(code);
        // this.clients.delete(client);
        console.log("connection lost.")
        console.log()
    }
}