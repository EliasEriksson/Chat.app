import {WebSocketClient} from "https://deno.land/x/websocket@v0.1.1/mod.ts";


export class Client {
    private socket: WebSocketClient;
    private messages: string[];

    constructor(ws: WebSocketClient) {
        this.socket = ws;
        this.messages = [];
        this.socket.addListener("message", (message: string) => {
            this.messages.push(message);
        })
    }

    async send(data: string) {
        this.socket.send(data);
    }

    receive = async () => {

    }

    async close(code: number) {
        return await this.socket.close(code);
    }
}