import {
    WebSocketClient, WebSocketServer
} from "https://deno.land/x/websocket@v0.1.1/mod.ts";

import {
    Client
} from "./client.ts";

import {
    DbManager
} from "./orm/dbManager.ts";

import {
    User
} from "./orm/models/users.ts";

import {
    UserProfile
} from "./orm/models/userProfiles.ts";
import {UnauthorizedError} from "./errors.ts";

import {
    Room
} from "./orm/models/room.ts";


export class Server {
    private socket: WebSocketServer;
    private rooms: Map<Room, Set<WebSocketClient>>;
    private dbManager: DbManager;

    constructor(port: number) {
        this.rooms = new Map<Room, Set<WebSocketClient>>();
        this.socket = new WebSocketServer(port);
        this.dbManager = new DbManager();
    }

    private authenticate = async (client: Client) => {
        // let messageData: string = await client.receive();

    }

    private handleConnection = async (wsc: WebSocketClient) => {
        let client = new Client(wsc);

        let message = await client.receive();
        console.log(message);
        message = await client.receive();
        console.log(message);



        // client.addListener("message", async (message: string) => {
        //     let messageData: { "sessionID": string, roomID: string } = JSON.parse(message);
        //     let user = await this.dbManager.getUserFromSession(messageData["sessionID"]);
        //     if (!user) {
        //         // authentication failed 401 unauthorized
        //         await this.close_connection(client, 401);
        //         return;
        //     }
        //     let rooms = await this.dbManager.getUserRooms(user);
        //     for (const room of rooms) {
        //         if (!this.rooms.has(room)) {
        //             this.rooms.set(room, new Set<WebSocketClient>());
        //         }
        //         // @ts-ignore
        //         this.rooms.get(room).add(client);
        //     }
        // });
        // client.addListener("message", this.authenticate);
    }

    async close_connection(client: WebSocketClient, code: number): Promise<never> {
        await client.close(code);
        throw new UnauthorizedError(code.toString());
    }

    public connect = async () => {
        await this.dbManager.connect();
        this.socket.addListener("connection", this.handleConnection);
        this.socket.addListener("close", this.close_connection);
        console.log("waiting for connections...")
    }
}