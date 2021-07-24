import {
    Client
} from "./client/client.js";


async function main() {
    const client = new Client();
    await client.open();
    await client.authenticate();
}

window.addEventListener("load", main);