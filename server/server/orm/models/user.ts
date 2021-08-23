export class User {
    private readonly id: string;
    private readonly sessionID: string;
    private readonly email: string;
    private readonly username: string;
    private readonly avatar: string;

    static fromObject(userData: {id: string, sessionID: string, email: string, username: string, avatar: string}) {
        return new this(
            userData["id"],
            userData["sessionID"],
            userData["email"],
            userData["username"],
            userData["avatar"]
        );
    }

    constructor(id: string, sessionID: string, email: string, username: string, avatar: string) {
        this.id = id;
        this.sessionID = sessionID;
        this.email = email;
        this.username = username;
        this.avatar = avatar;
    }

    getID = (): string => {
        return this.id;
    }

    getSessionID = () => {
        return this.sessionID;
    }

    getEmail = (): string => {
        return this.email;
    }

    getUsername = (): string => {
        return this.username;
    }

    getAvatar = (): string => {
        return this.avatar;
    }
}