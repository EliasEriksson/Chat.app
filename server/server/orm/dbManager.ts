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

export class DbManager {
    private client: Client;
    constructor() {
        this.client = new Client();
    }

    async connect() {
        const credentials: {
            hostname: string, username: string, db: string, password: string
        } = JSON.parse(await Deno.readTextFile(".credentials.json"));
        await this.client.connect(credentials);
    }

    async getUser(id: string): Promise<User|null> {
        let userData = (await this.client.query(
            "select * from users where id = ?;"
            , [id]
        ))[0];
        if (userData) {
            return User.fromObject(userData);
        }
        return null;
    }

    async getUserFromSession(sessionID: string) {
        let userData = (await this.client.query(
            "select * from users where id = (select userID from sessions where sessionID = ?);",
            [sessionID]
        ))[0];
        if (userData) {
            console.log(userData)
            return User.fromObject(userData)
        }
    }

    async getUserProfile(user: User): Promise<UserProfile|null> {
        let userProfileData = (await this.client.query(
            "select * from userProfiles where userID = ?;",
            [user.getID()]
        ))[0];
        if (userProfileData) {
            return UserProfile.fromObject(userProfileData);
        }
        return null
    }

    async getSession(user: User): Promise<Session|null> {
        let sessionData = (await this.client.query(
            "select * from sessions where userID = ?",
            [user.getID()]
        ))[0];
        if (sessionData) {
            return Session.fromObject(sessionData);
        }
        return null;
    }
}