"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var mod_ts_1 = require("https://deno.land/x/websocket@v0.1.1/mod.ts");
var endpoint = "ws://127.0.0.1:8080";
var ws = new mod_ts_1.StandardWebSocketClient(endpoint);
ws.on("open", function () {
    console.log("ws connected!");
});
ws.on("message", function (message) {
    console.log(message);
});
ws.send("something");
