export class Session {
    private readonly userID: string;
    private readonly sessionID: string;

    static fromObject(userData: {userID: string, sessionID: string}) {
        return new this(
            userData["userID"],
            userData["sessionID"]
        );
    }

    constructor(id: string, email: string) {
        this.userID = id;
        this.sessionID = email;
    }

    getUserID(): string {
        return this.userID;
    }

    getUsername(): string {
        return this.sessionID;
    }
}