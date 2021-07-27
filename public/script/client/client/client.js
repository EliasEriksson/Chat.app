

export class Client {
    constructor(sessionID, roomID, url) {
        this.url =
        this.sessionID = sessionID;
        this.roomID = roomID;
        this.socket = new WebSocket(url);
    }

    send = (data) => {
        this.socket.send(JSON.stringify({
            "content": data,
            "roomID": "", // TODO return here after a user can create rooms
            "session": this.sessionID,
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
        await this.open();
        this.socket.send(JSON.stringify({
            "sessionID": this.sessionID,
            "roomID": this.roomID
        }));
    }

    requestTemplate = async () => {
        return  await (await fetch("templates/message.html")).text();
    }

    connect = async () => {
        let template = (await Promise.allSettled([this.authenticate(), this.requestTemplate()]))[1]

        this.socket.addEventListener("message", (event) => {
            let html = render(template, JSON.parse(event.data));

        });
    }
}