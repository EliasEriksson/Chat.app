import {
    WebSocketClient
} from "https://deno.land/x/websocket@v0.1.1/mod.ts";

import {
    Queue
} from "https://deno.land/x/async/mod.ts";

export class Client {
    private socket: WebSocketClient;
    private messageQueue: Queue<string>;

    constructor(ws: WebSocketClient) {
        this.socket = ws;
        this.messageQueue = new Queue();
        this.socket.addListener("message", async (message: string) => {
            await this.messageQueue.put(message);
        });
    }

    send = (data: string) => {
        this.socket.send(data);
    }

    receive = async () => {
        return await this.messageQueue.get();
    }
}