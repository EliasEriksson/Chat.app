import { getSessionID} from "./cookies.js";


export class Client {
    constructor(url) {
        this.socket = new WebSocket(url);
    }

    async open() {
        return new Promise((resolve => {
            this.socket.addEventListener("open", (event) => {
                resolve(event);
            });
        }));
    }

    async authenticate() {
        await this.send(getSessionID());

    }

    async send(data) {
        this.socket.send(data);
        console.log(`sent: ${data}`);
    }

    async receive() {
        return new Promise((resolve => {
            this.socket.addEventListener("message", (message) => {
                resolve(message);
            });
        }));
    }
}