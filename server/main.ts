import { Server } from "./server/server.ts";

const server = new Server(8080);
await server.connect();