import {
    render
} from "../xrender.js";


const getAttributeFromFirstHtmlNode = (element, attributeName) => {
    element = element.firstChild;
    while (element.nodeType === 3) {
        element = element.nextSibling;
        if (element === null) {
            return null;
        }
    }
    return element.getAttribute(attributeName);
}


export class HistoryLoader {
    constructor(roomID, chatFeedElement, template) {
        this.api = "/api/getMessages/?";
        this.roomID = roomID;
        this.chatFeedElement = chatFeedElement;
        this.template = template;
        this.lastRendered = getAttributeFromFirstHtmlNode(chatFeedElement, "data-id");
        this.loading = false;
        this.fullyConsumed = false;
    }

    loadHistory = async () => {
        console.log("was called")
        if (this.fullyConsumed || this.loading) {
            console.log("fully consumed or loading")
            return;
        }
        this.loading = true;

        console.log("initiating request")
        let url;
        if (this.lastRendered) {
            url = `${this.api}roomID=${this.roomID}&before=${this.lastRendered}`;
        } else {
            url = `${this.api}roomID=${this.roomID}`
        }
        console.log(url);
        let response = await (await fetch(url)).json();

        if (!response.length) {
            console.log("fully consumed")
            this.fullyConsumed = true;
            return;
        }

        console.log("rendering...")
        let html;
        for (let message of response) {
            html = render(this.template, message);
            this.chatFeedElement.prepend(html);
            this.lastRendered = message.id;
            console.log("rendered html")
        }
        console.log("rendering done.")
        this.loading = false;
        console.log("no longer loading")
    }
}