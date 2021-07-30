import {
    Client
} from "https://deno.land/x/mysql/mod.ts";

import {
    User
} from "./models/user.ts";

import {
    Room
} from "./models/room.ts";

export class DbManager {
    private client: Client;

    constructor() {
        this.client = new Client();
    }

    connect = async () => {
        const credentials: {
            hostname: string, username: string, db: string, password: string
        } = JSON.parse(await Deno.readTextFile(".credentials.json"));
        await this.client.connect(credentials);
    }

    getRoom = async (roomID: string): Promise<Room | null> => {
        let roomData = (await this.client.query(
            "select bin_to_uuid(id) as id, name \
             from rooms \
             where id = uuid_to_bin(?);",
            [roomID]
        ))[0];
        if (roomData) {
            return Room.fromJSON(roomData);
        }
        return null;
    }

    getUserFromSession = async (sessionID: string): Promise<User | null> => {
        let userData = (await this.client.query(
            "select bin_to_uuid(users.id) as id, email, username, avatar \
             from users join userProfiles on users.id = userProfiles.userID \
             where users.id = ( \
                select userID \
                from sessions \
                where sessionID = ? \
             );",
            [sessionID]
        ))[0];
        if (userData) {
            userData["sessionID"] = sessionID;
            return User.fromObject(userData)
        }
        return null;
    }
}