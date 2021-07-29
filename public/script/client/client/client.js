import {
    render
} from "../xrender.js";

import {
    Queue
} from "./queue.js";

export class Client {
    constructor(sessionID, roomID, url, chatFeedElement) {
        this.url = url;
        this.sessionID = sessionID;
        this.roomID = roomID;
        this.chatFeedElement = chatFeedElement;
        this.messageQueue = new Queue();
    }

    open = async () => {
        return new Promise((resolve => {
            this.socket = new WebSocket(this.url);
            this.socket.addEventListener("message", (event) => {
                this.messageQueue.put(event.data);
            });
            this.socket.addEventListener("open", () => {
                resolve();
            });
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
        let json = await this.messageQueue.get();
        return JSON.parse(json);
    }

    authenticate = async () => {
        await this.open();
        this.socket.send(JSON.stringify({
            "sessionID": this.sessionID,
            "roomID": this.roomID
        }));
        await this.receive();
    }

    requestTemplate = async () => {
        return await (await fetch("/templates/message.html")).text();
    }

    connect = async () => {
        let template = (await Promise.allSettled([this.authenticate(), this.requestTemplate()]))[1]

        let message, html;
        while (message = await this.receive()) {
            html = render(template, message.content);
            this.chatFeedElement.appendChild(html);
        }
    }
}