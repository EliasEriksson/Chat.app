export class UserProfile {
    private readonly userID: string;
    private readonly username: string;
    private readonly avatar: string;

    static fromObject(userData: {userID: string, username: string, avatar: string}) {
        return new this(
            userData["userID"],
            userData["username"],
            userData["avatar"]
        );
    }

    constructor(id: string, email: string, passwordHash: string) {
        this.userID = id;
        this.username = email;
        this.avatar = passwordHash;
    }

    getUserID(): string {
        return this.userID;
    }

    getUsername(): string {
        return this.username;
    }

    getAvatar(): string {
        return this.avatar;
    }
}