import {
    Client
} from "https://deno.land/x/mysql/mod.ts";

import {
    User
} from "./models/user.ts";

import {
    Room
} from "./models/room.ts";
import {Message} from "./models/message.ts";


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

    createMessage = async (user: User, room: Room, message: string): Promise<Message | null> => {
        // @ts-ignore this is included in deno but not in typescript rule set
        let id: string = crypto.randomUUID();
        let date = Math.round(new Date().getTime() / 1000);

        let result = await this.client.execute(
            "insert into messages values (uuid_to_bin(?), uuid_to_bin(?), uuid_to_bin(?), from_unixtime(?), ?)",
            [id, user.getID(), room.getID(), date, message]
        )
        if (result) {
            return new Message(id, user, room, date, message);
        }
        return null;
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

    getRoomUserList = async (room: Room): Promise<User[]> => {
        let usersData = (await this.client.query(
            "select bin_to_uuid(users.id) as id, email, username, avatar \
             from ((select userID \
                 from members where roomID = uuid_to_bin(?)) as m \
                 join users on m.userID = users.id join userProfiles on m.userID = userProfiles.userID);",
            [room.getID()]
        ));
        if (usersData) {
            return usersData;
        }
        return [];
    }
}
