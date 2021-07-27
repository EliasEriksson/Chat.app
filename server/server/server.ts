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

export class Server {
    private socket: WebSocketServer;
    private clients: Set<Client>;
    private dbManager: DbManager|null;

    constructor(port: number) {
        this.clients = new Set<Client>();
        this.socket = new WebSocketServer(port);
        this.dbManager = null;
        this.socket.addListener("connection", this.handleConnection);
        this.socket.addListener("close", this.close_connection);
        console.log("waiting for connections...")
    }

    private getDbManager = async (): Promise<DbManager> => {
        if (!this.dbManager) {
            this.dbManager = new DbManager();
            await this.dbManager.connect();
            return this.dbManager;
        }
        return this.dbManager;
    }

    private authenticate = async (sessionID: string): Promise<[User|null, UserProfile|null]> => {
         let user = await (await this.getDbManager()).getUserFromSession(sessionID);
         if (user) {
             let userProfile = await (await this.getDbManager()).getUserProfile(user);
             if (userProfile) {
                 return [user, userProfile]
             }
             return [user, null];
         }
         return [null, null];
    }

    private handleConnection = async (ws: WebSocketClient) =>{
        console.log("somone connected.");
        let client = new Client(ws);
        let data = await client.receive();
        console.log(`authenticating with session ${data}`);
        let user = await (await this.getDbManager()).getUserFromSession(data);
        if (user) /* AUTHENTICATED */ {
            let userProfile = await (await this.getDbManager()).getUserProfile(user);
            console.log(`Welcome ${userProfile?.getUsername()}!`);
        } else {
            console.log("You are not allowed...");
        }
    }

    async close_connection(client: Client, code: number) {
        await client.close(code);
        // this.clients.delete(client);
        console.log("connection lost.")
        console.log()
    }
}