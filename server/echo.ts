import { serve } from "https://deno.land/x/websocket_server/mod.ts";
const server = serve(":8080");
console.log("server running")
for await (const request of server) {
    console.log(request);
}