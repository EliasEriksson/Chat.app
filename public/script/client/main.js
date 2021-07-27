import {
    Client
} from "./client/client.js";
import {
    getSessionID
} from "./client/cookies.js";


const main = () => {
    const script = document.currentScript;
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

        const client = new Client(sessionID, roomID, url);
        await client.connect();
    });
}

main();