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
        console.log("waiting for client to send credentials...");
        let messageData = await client.receive();
        console.log("received client credentials.")
        let sessionID = messageData["sessionID"];
        let roomID = messageData["roomID"];

        let user = await this.dbManager.getUserFromSession(sessionID);
        if (!user) {
            throw new UnauthorizedError("unknown user.");
        }

        let room = await this.dbManager.getRoom(roomID);
        if (!room) {
            let message = `user ${user.getID()} is not a member of the room ${roomID}`;
            throw new UnauthorizedError(message);
        }

        await client.send({
            "status": 200
        });
        console.log(`user ${user.getUsername()} successfully authenticated.`)
        return [user, room];
    }

    private serve = async (client: Client, user: User, room: Room) => {
        let request: { [key: string]: string };
        let message: Message|null
        console.log("waiting for a request from a client...");
        while (request = await client.receive()) {
            console.log("message received from a client.")
            if (request.ping) {
                console.log("message was a ping.")
                continue;
            }

            message = await this.dbManager.createMessage(user, room, request.content);
            if (!message) {
                console.log("message could not be created for some reason.")
                continue;
            }

            console.log("sending out message to all connected clients in this room...")
            for (let roomClient of this.rooms.get(room.getID())!) {
                try {
                    await roomClient.send({
                        "id": message.getID(),
                        "userID": user.getID(),
                        "email": user.getEmail(),
                        "username": user.getUsername(),
                        "avatar": user.getAvatar(),
                        "content": request.content,
                        "postDate": message.getPostDate()
                    });
                } catch (error) {
                    if (error instanceof ConnectionAborted) {
                        console.log("client went away. disconnecting.");
                        this.removeClient(client, room);
                    } else {
                        throw error;
                    }
                }
            }
            console.log("message sent to all clients.")
            console.log("waiting for a request from the client...");
        }
    }

    private handleConnection = async (wsc: WebSocketClient) => {
        let client = new Client(wsc);
        let user: User, room: Room;
        try {
            [user, room] = await this.authenticate(client);
        } catch (error) {
            if (error instanceof ConnectionAborted) {
                console.log("a client went away. disconnecting.");
                return;
            } else if (error instanceof UnauthorizedError) {
                console.log("user failed to authorise. disconnecting.");
                return;
            }
            console.log(`uncaught error:\n${error}\n`);
            return;
        }

        this.addClient(client, room);

        try {
            console.log("starting to serve a client.");
            await this.serve(client, user, room);
        } catch (error) {
            if (error instanceof ConnectionAborted) {
                return;
            }
            console.log(`uncaught error:\n${error}\n`);
            return;
        }
    }

    public connect = async () => {
        await this.dbManager.connect();
        this.socket.addListener("connection", this.handleConnection);
        console.log("waiting for connections...");
    }
}