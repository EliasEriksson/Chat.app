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
} from "./orm/models/user.ts";

import {
    ConnectionAborted,
    UnauthorizedError
} from "./errors.ts";

import {
    Room
} from "./orm/models/room.ts";


export class Server {
    private socket: WebSocketServer;
    private readonly rooms: Map<string, Set<Client>>;
    private dbManager: DbManager;

    constructor(port: number) {
        this.rooms = new Map<string, Set<Client>>();
        this.socket = new WebSocketServer(port);
        this.dbManager = new DbManager();
    }

    private authenticate = async (client: Client): Promise<[User, Room]> => {
        let messageData = await client.receive();
        let sessionID = messageData["sessionID"];
        let roomID = messageData["roomID"];

        let user = await this.dbManager.getUserFromSession(sessionID);
        if (!user) {
            throw new UnauthorizedError("Unknown user.");
        }

        let room = await this.dbManager.getRoom(roomID);
        if (!room) {
            let message = `User ${user.getID()} is not a member of the room ${roomID}`;
            throw new UnauthorizedError(message);
        }

        client.send({
            "status": 200
        });
        return [user, room];
    }


    private serve = async (client: Client, user: User, room: Room) => {
        let message: { [key: string]: string };
        while (message = await client.receive()) {
            if (message.ping) {
                console.log(`${user.getID()} pinged.`)
                continue;
            }
            for (let roomClient of this.rooms.get(room.getID())!) {
                roomClient.send({
                    "content": message.content,
                    "userID": user.getID(),
                    "username": user.getUsername(),
                    "avatar": user.getAvatar(),
                });
            }
        }
    }

    private handleConnection = async (wsc: WebSocketClient) => {

        let client = new Client(wsc);

        let [user, room] = await this.authenticate(client);
        console.log(user.getSessionID())

        if (!this.rooms.has(room.getID())) {
            this.rooms.set(room.getID(), new Set<Client>());
        }
        this.rooms.get(room.getID())!.add(client);
        console.log(this.rooms);

        try {
            await this.serve(client, user, room);
        } catch (error) {
            if (error instanceof ConnectionAborted) {
                if (this.rooms.has(room.getID())) {
                    let clientRoom = this.rooms.get(room.getID())!
                    if (clientRoom.has(client)) {
                        clientRoom.delete(client);
                    }
                    if (!clientRoom.size) {
                        this.rooms.delete(room.getID());
                    }
                }
                console.log(`user ${user.getID()} left room ${room.getID()}`)
                console.log(this.rooms);
            } else {
                console.log(error);
            }
        }
    }

    public connect = async () => {
        await this.dbManager.connect();
        this.socket.addListener("connection", this.handleConnection);
        console.log("waiting for connections...");
    }
}