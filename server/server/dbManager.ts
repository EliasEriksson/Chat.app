import { Client } from "https://deno.land/x/mysql/mod.ts";

const credentials = JSON.parse(await Deno.readTextFile(".credentials.json"));

const client = await new Client().connect({
    "hostname": credentials["DB_HOSTNAME"],
    "username": credentials["DB_USER"],
    "password": credentials["DB_PASS"],
    "db": credentials["DB_NAME"]
});
console.log(client)

