import {
    Client
} from "./client/client.js";
import {
    getSessionID
} from "./client/cookies.js";


const main = () => {
    const script = document.getElementById("client-module");
    let [subDomain, domain, topDomain] = document.location.hostname.split(".");
    let url;
    if (!topDomain) {
        // no top level domain. probably localhost
        url = `ws://connect.${domain}`;
    } else {
        topDomain = topDomain.split("/")[0];
        url = `ws://connect.${domain}.${topDomain}`;
    }
    window.addEventListener("load", async () => {
        let roomID = script.getAttribute("data-roomID");
        let sessionID = getSessionID();
        let chatFeedElement = document.getElementById(script.getAttribute("data-chatFeedElementID"));
        let chatBoxElement = document.getElementById(script.getAttribute("data-chatBoxElementID"))
        let chatSendElement = document.getElementById(script.getAttribute("data-chatSendElementID"));

        const client = new Client(sessionID, roomID, url, chatFeedElement);
        await client.open();

        chatSendElement.addEventListener("click", ()=> {
            let message = chatBoxElement.value;
            client.send(message);
            chatBoxElement.value = "";
        });

        await client.connect();
    });
}

main();