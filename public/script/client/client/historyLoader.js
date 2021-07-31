import {
    render
} from "../xrender.js";


export class HistoryLoader {
    constructor(roomID, chatFeedElement, template) {
        this.api = "/api/getMessages/?";
        this.roomID = roomID;
        this.chatFeedElement = chatFeedElement;
        this.template = template;
        this.lastRendered = chatFeedElement.firstChild;
        this.loading = false;
        this.fullyConsumed = false;
    }

    loadHistory = async () => {
        if (this.fullyConsumed || this.loading) {
            return;
        }
        this.loading = true;

        let response;
        if (this.lastRendered) {
            response = await (await fetch(`${this.api}roomID=${this.roomID}&before=${this.lastRendered}`)).json();
        } else {
            response = await (await fetch(`${this.api}roomID=${this.roomID}`)).json();
        }
        if (!response.length) {
            this.fullyConsumed = true;
            return;
        }

        let html;
        for (let message of response) {
            html = render(this.template, message);
            this.chatFeedElement.prepend(html);
            this.lastRendered = message.id;
        }
        this.loading = false;
    }
}