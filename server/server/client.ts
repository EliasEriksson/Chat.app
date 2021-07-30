import {
    WebSocketClient
} from "https://deno.land/x/websocket@v0.1.1/mod.ts";


const wait = async (): Promise<void> => {
    return new Promise(resolve => {
        setTimeout(() => {
            resolve();
        }, 10);
    });
}


export class Client {
    private socket: WebSocketClient;
    private messageQueue: string[];

    constructor(ws: WebSocketClient) {
        this.socket = ws;
        this.messageQueue = [];
        this.socket.addListener("message", async (message: string) => {
            await this.messageQueue.push(message);
        });
    }

    send = (data: {[key: string]: any}) => {
        this.socket.send(JSON.stringify(data));
    }

    receive = async (): Promise<{[key: string]: string}> => {
        let jsonString;
        while (!(jsonString = this.messageQueue.shift())) {
            await wait();
        }
        return JSON.parse(jsonString);
    }
}