import {
    render
} from "../xrender.js";

import {
    formatUnixTime
} from "./time.js";

import {
    UnauthorizedError
} from "./errors.js";

const wait = async () => {
    return new Promise(resolve => {
        setTimeout(resolve, 10);
    });
}


export class Client {
    constructor(sessionID, roomID, url, chatFeedElement, template) {
        this.url = url;
        this.sessionID = sessionID;
        this.roomID = roomID;
        this.chatFeedElement = chatFeedElement;
        this.template = template;
        this.messageQueue = [];
        this._open = false;

        this.socket = new WebSocket(this.url);
        this.socket.addEventListener("open", () => {
            this._open = true;
            setInterval(this.ping, 30000);
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
            "ping": {
                "ping": "pong"
            }
        }));
    }

    requestMessage = (data) => {
        this.socket.send(JSON.stringify({
            "message": {
                "content": data,
                "roomID": this.roomID,
                "session": this.sessionID
            }
        }));
    }

    requestRoomUserList = () => {
        this.socket.send(JSON.stringify({
            "roomUserList": {}
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
        console.log("connection with the server is established.");
        console.log("sending credentials to the server...");
        this.socket.send(JSON.stringify({
            "authenticate": {
                "sessionID": this.sessionID,
                "roomID": this.roomID
            }
        }));
        console.log("credentials have been sent to the server.");

        console.log("awaiting server to respond...");
        let response = await this.receive();
        if (response.status === 200) {
            console.log("server responded with 200.");
            return;
        }
        console.log("server did not allow a connection.");
        throw new UnauthorizedError();
    }

    messageResponse = (message) => {
        message.postDate = formatUnixTime(message.postDate);
        let html = render(this.template, message);
        this.chatFeedElement.appendChild(html);
    }

    roomUserListResponse = (roomUserList) => {
        // TODO update the roomUserList on the page show online etc.
        // to be able to show online status such info must first be sent from the server.
    }

    connect = async () => {
        try {
            await this.authenticate();
            let response;
            console.log("awaiting response from the server...");
            while (response = await this.receive()) {
                console.log(`this is the response object`, response);
                if (response.message) {
                    this.messageResponse(response.message);
                } else if (response.roomUserList) {
                    this.roomUserListResponse(response.roomUserList);
                }
            }
        } catch (error) {
            if (error instanceof UnauthorizedError) {
                this.socket.close();
            } else {
                this.socket.close();
                throw error;
            }
        }
    }
}
