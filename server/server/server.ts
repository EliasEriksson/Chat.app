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
    Message
} from "./orm/models/message.ts";

import {
    Room
} from "./orm/models/room.ts";

import {
    ConnectionAborted,
    UnauthorizedError
} from "./errors.ts";


export class Server {
    private socket: WebSocketServer;
    private readonly rooms: Map<string, Set<Client>>;
    private dbManager: DbManager;

    constructor(port: number) {
        this.rooms = new Map<string, Set<Client>>();
        this.socket = new WebSocketServer(port);
        this.dbManager = new DbManager();
    }

    private addClient = (client: Client, room: Room) => {
        if (!this.rooms.has(room.getID())) {
            this.rooms.set(room.getID(), new Set<Client>());
        }
        this.rooms.get(room.getID())!.add(client);
    }

    private removeClient = (client: Client, room: Room) =>  {
        if (this.rooms.has(room.getID())) {
            let clientRoom = this.rooms.get(room.getID())!
            if (clientRoom.has(client)) {
                clientRoom.delete(client);
            }
            if (!clientRoom.size) {
                this.rooms.delete(room.getID());
            }
        }
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
        try {
            client.send({
                "status": 200
            });
        } catch (error) {
            if (error instanceof Deno.errors.ConnectionReset) {
                console.log("caught error")
            }
            throw error;
        }

        return [user, room];
    }

    private serve = async (client: Client, user: User, room: Room) => {
        let request: { [key: string]: string };
        let message: Message|null
        while (request = await client.receive()) {
            if (request.ping) {
                continue;
            }

            message = await this.dbManager.createMessage(user, room, request.content);
            if (!message) {
                continue;
            }

            for (let roomClient of this.rooms.get(room.getID())!) {
                roomClient.send({
                    "id": message.getID(),
                    "userID": user.getID(),
                    "email": user.getEmail(),
                    "username": user.getUsername(),
                    "avatar": user.getAvatar(),
                    "content": request.content,
                    "postDate": message.getPostDate()
                });
            }
        }
    }

    private handleConnection = async (wsc: WebSocketClient) => {
        let client = new Client(wsc);
        let user: User, room: Room;
        try {
            [user, room] = await this.authenticate(client);
        } catch (error) {
            if (error instanceof ConnectionAborted
                || error instanceof Deno.errors.ConnectionReset
                || error instanceof Deno.errors.ConnectionAborted
                || error instanceof Deno.errors.ConnectionRefused
                || error instanceof Deno.errors.NotConnected) {
                console.log("an error was caught.")
                return;
            }
            console.log("an error was not caught")
            throw error;
        }

        console.log(user.getSessionID())

        this.addClient(client, room);
        console.log(this.rooms);

        try {
            await this.serve(client, user, room);
        } catch (error) {
            if (error instanceof ConnectionAborted
                || error instanceof Deno.errors.ConnectionReset
                || error instanceof Deno.errors.ConnectionAborted
                || error instanceof Deno.errors.ConnectionRefused
                || error instanceof Deno.errors.NotConnected) {
                this.removeClient(client, room);
                return;
            }
            throw error;
        }
    }

    public connect = async () => {
        await this.dbManager.connect();
        this.socket.addListener("connection", this.handleConnection);
        console.log("waiting for connections...");
    }
}