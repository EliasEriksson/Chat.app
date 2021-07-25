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
        this.sessionID = getSessionID();
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
        this.socket.send(this.sessionID);
    }

    send = async(data) => {
        this.socket.send(JSON.stringify({
            "content": data,
            "roomID": "", // TODO return here after a user can create rooms
            "session": this.sessionID,
        }));
    }

    receive = async() => {
        return new Promise((resolve => {
            this.socket.addEventListener("message", (message) => {
                resolve(message);
            });
        }));
    }
}