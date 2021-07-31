import {
    Client
} from "./client/client.js";

import {
    getSessionID
} from "./client/cookies.js";

import {
    HistoryLoader
} from "./client/historyLoader.js";


const getConnectionURL = () => {
    let [subDomain, domain, topDomain] = document.location.hostname.split(".");
    if (!topDomain) {
        // no top level domain. probably localhost
        return `ws://connect.${domain}`;
    }
    topDomain = topDomain.split("/")[0];
    return `ws://connect.${domain}.${topDomain}`;
}


const isInViewport = (element) => {
    let rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= window.innerHeight &&
        rect.right <= window.innerWidth
    );
}


const main = () => {
    const script = document.getElementById("client-module");
    let url = getConnectionURL();
    let roomID = script.getAttribute("data-roomID");
    let sessionID = getSessionID();
    let chatFeedElement = document.getElementById(script.getAttribute("data-chatFeedElementID"));
    let chatBoxElement = document.getElementById(script.getAttribute("data-chatBoxElementID"));
    let chatSendElement = document.getElementById(script.getAttribute("data-chatSendElementID"));
    let testLoadHistory = document.getElementById("load-history");

    window.addEventListener("load", async () => {
        const template = await (await fetch("/templates/message.html")).text();

        const client = new Client(sessionID, roomID, url, chatFeedElement, template);
        const historyLoader = new HistoryLoader(roomID, chatFeedElement, template);

        chatSendElement.addEventListener("click", () => {
            let message = chatBoxElement.value;
            client.send(message);
            chatBoxElement.value = "";
        });

        await client.connect();

        testLoadHistory.addEventListener("click", async () => {
            await historyLoader.loadHistory();
        });
    });


}

main();