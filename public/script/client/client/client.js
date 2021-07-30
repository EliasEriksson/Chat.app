import {
    render
} from "../xrender.js";

import {
    UnauthorizedError
} from "./errors.js";

const wait = async () => {
    return new Promise(resolve => {
        setTimeout(() =>  resolve());
    });
}


export class Client {
    constructor(sessionID, roomID, url, chatFeedElement) {
        this.url = url;
        this.sessionID = sessionID;
        this.roomID = roomID;
        this.chatFeedElement = chatFeedElement;
        this.messageQueue = [];
        this._open = false;

        this.socket = new WebSocket(this.url);
        this.socket.addEventListener("open", () => {
            this._open = true;
            setInterval(async () => this.ping(), 30000);
        });
        this.socket.addEventListener("message", (event) => {
            let message = JSON.parse(event.data);
            if (message.ping) {

            } else {
                this.messageQueue.push(message);
            }
        });
    }

    open = async () => {
        while (!this._open) {
            await wait();
        }
    }

    ping = () => {
        this.socket.send(JSON.stringify({
            "ping": "pong"
        }));
    }

    send = (data) => {
        this.socket.send(JSON.stringify({
            "content": data,
            "roomID": this.roomID,
            "session": this.sessionID
        }));
    }

    receive = async () => {
        let message;
        while (!(message = this.messageQueue.shift())) {
            await wait();
        }
        return message;
    }

    authenticate = async () => {
        await this.open();
        this.socket.send(JSON.stringify({
            "sessionID": this.sessionID,
            "roomID": this.roomID
        }));

        let response = await this.receive();
        if (response.status === 200) {
            return;
        }
        throw new UnauthorizedError();
    }

    requestTemplate = async () => {
        return await (await fetch("/templates/message.html")).text();
    }

    connect = async () => {
        try {
            await this.authenticate();
            let template = await this.requestTemplate();
            let message, html;
            while (message = await this.receive()) {
                html = render(template, {
                    "user": message.username,
                    "content": message.content
                });
                this.chatFeedElement.appendChild(html);
            }
        } catch (error) {
            if (error instanceof UnauthorizedError) {
                this.socket.close(401);
            } else {
                this.socket.close(503);
            }
        }
    }
}
