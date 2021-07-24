import {
    getSessionID
} from "./cookies.js";


export class Client {
    constructor() {
        let [subDomain, domain, topDomain] = document.location.hostname.split(".");
        let url;
        if (!topDomain) {
            // no top level domain. probably localhost
            url = `ws://connect.${domain}`;
        } else {
            topDomain = topDomain.split("/")[0];
            url = `ws://connect.${domain}.${topDomain}`;
        }
        this.socket = new WebSocket(url);
    }

    open = () => {
        return new Promise((resolve => {
            this.socket.addEventListener("open", (event) => {
                resolve(event);
            });
        }));
    }

    authenticate = async () => {
        await this.send(getSessionID());

    }

    send = async(data) => {
        this.socket.send(data);
        console.log(`sent: ${data}`);
    }

    receive = async() => {
        return new Promise((resolve => {
            this.socket.addEventListener("message", (message) => {
                resolve(message);
            });
        }));
    }
}