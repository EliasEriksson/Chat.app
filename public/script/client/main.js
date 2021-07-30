import {
    Client
} from "./client/client.js";
import {
    getSessionID
} from "./client/cookies.js";


const getConnectionURL = () => {
    let [subDomain, domain, topDomain] = document.location.hostname.split(".");
    if (!topDomain) {
        // no top level domain. probably localhost
        return `ws://connect.${domain}`;
    }
    topDomain = topDomain.split("/")[0];
    return `ws://connect.${domain}.${topDomain}`;
}


const main = () => {
    const script = document.getElementById("client-module");

    window.addEventListener("load", async () => {
        let url = getConnectionURL();
        let roomID = script.getAttribute("data-roomID");
        let sessionID = getSessionID();
        let chatFeedElement = document.getElementById(script.getAttribute("data-chatFeedElementID"));
        let chatBoxElement = document.getElementById(script.getAttribute("data-chatBoxElementID"))
        let chatSendElement = document.getElementById(script.getAttribute("data-chatSendElementID"));

        const client = new Client(sessionID, roomID, url, chatFeedElement);

        chatSendElement.addEventListener("click", () => {
            let message = chatBoxElement.value;
            client.send(message);
            chatBoxElement.value = "";
        });

        await client.connect();
    });
}

main();