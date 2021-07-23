import {WebSocketClient} from "https://deno.land/x/websocket@v0.1.1/mod.ts";


export class Client {
    private socket: WebSocketClient;

    constructor(ws: WebSocketClient) {
        this.socket = ws;
    }

    async send(data: string) {
        this.socket.send(data);
    }

    async receive() {
        return new Promise<string>(resolve => {
            this.socket.addListener("message", (message: string) => {
                resolve(message);
            });
        })
    }

    async close(code: number) {
        return await this.socket.close(code);
    }
}