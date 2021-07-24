import {
    Client
} from "./client/client.js";
import {
    getSessionID
} from "./client/cookies.js";

let [subDomain, domain, topDomain] = document.location.hostname.split(".");
let url;
if (!topDomain) {
    // no top level domain. probably localhost
    url = `ws://connect.${domain}`;
} else {
    topDomain = topDomain.split("/")[0];
    url = `ws://connect.${domain}.${topDomain}`;
}

async function main() {
    const client = new Client(url);
    await client.open();
    await client.send(getSessionID());
}

window.addEventListener("load", main);