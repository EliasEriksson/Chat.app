import {
    render
} from "../xrender.js";

export class Client {
    constructor(sessionID, roomID, url, chatFeedElement) {
        this.url = url;
        this.sessionID = sessionID;
        this.roomID = roomID;
        this.socket = new WebSocket(url);
        this.chatFeedElement = chatFeedElement;
    }

    send = (data) => {
        this.socket.send(JSON.stringify({
            "content": data,
            "roomID": this.roomID,
            "session": this.sessionID
        }));
    }

    open = async () => {
        return new Promise((resolve => {
            this.socket.addEventListener("open", (event) => {
                resolve(event);
            });
        }));
    }

    authenticate = async () => {
        this.socket.send(JSON.stringify({
            "sessionID": this.sessionID,
            "roomID": this.roomID
        }));
    }

    requestTemplate = async () => {
        return await (await fetch("templates/message.html")).text();
    }

    connect = async () => {
        let template = (await Promise.allSettled([this.authenticate(), this.requestTemplate()]))[1]

        this.socket.addEventListener("message", (event) => {
            let html = render(template, JSON.parse(event.data));
            this.chatFeedElement.appendChild(html);
        });
    }
}