import {
    WebSocketClient
} from "https://deno.land/x/websocket@v0.1.1/mod.ts";
import {ConnectionAborted} from "./errors.ts";


const wait = async (): Promise<void> => {
    return new Promise(resolve => {
        setTimeout(() => resolve(), 10);
    });
}


export class Client {
    private socket: WebSocketClient;
    private messageQueue: string[];
    private open: boolean;

    constructor(ws: WebSocketClient) {
        this.socket = ws;
        this.messageQueue = [];

        this.open = true;
        this.socket.addListener("message", async (message: string) => {
            await this.messageQueue.push(message);
        });

        this.socket.addListener("close", () => {
            this.open = false;
        })

    }

    send = async (data: { [key: string]: any }) => {
        try {
            await this.socket.send(JSON.stringify(data));
        } catch (error) {
            if (error instanceof Deno.errors.ConnectionReset) {
                throw new ConnectionAborted("client bailed.")
            }
            throw error;
        }
    }

    receive = async (): Promise<{ [key: string]: string }> => {
        let jsonString;
        while (this.open) {
            if (!(jsonString = this.messageQueue.shift())) {
                await wait();
            } else {
                return JSON.parse(jsonString);
            }
        }
        throw new ConnectionAborted("client bailed");
    }
}
