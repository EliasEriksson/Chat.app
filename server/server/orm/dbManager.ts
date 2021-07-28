import {
    Client
} from "https://deno.land/x/mysql/mod.ts";

import {
    User
} from "./models/users.ts";

import {
    UserProfile
} from "./models/userProfiles.ts";

import {
    Session
} from "./models/sessions.ts";
import {Room} from "./models/room.ts";

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

    getRoom = async (roomID: string): Promise<Room|null> => {
        let roomData = (await this.client.query(
            "select bin_to_uuid(id) as id, name \
             from rooms \
             where id = ?;",
            [roomID]
        ))[0];
        if (roomData) {
            return Room.fromJSON(roomData);
        }
        return null;
    }

    getUserRoom = async (user: User) => {
        let roomData = await this.client.query(
            "select from rooms"
        )
    }

    getUserFromSession = async (sessionID: string): Promise<User|null> => {
        let userData = (await this.client.query(
            "select bin_to_uuid(id) as id, email, passwordHash \
             from users \
             where id = (select userID from sessions where sessionID = ?);",
            [sessionID]
        ))[0];
        if (userData) {
            return User.fromObject(userData)
        }
        return null;
    }

    getUserProfile = async (user: User): Promise<UserProfile|null> => {
        let userProfileData = (await this.client.query(
            "select bin_to_uuid(userID) as userID, username, avatar \
             from userProfiles \
             where userID = uuid_to_bin(?);",
            [user.getID()]
        ))[0];
        if (userProfileData) {
            return UserProfile.fromObject(userProfileData);
        }
        return null
    }
}