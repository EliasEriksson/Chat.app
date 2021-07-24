export class User {
    private readonly id: string;
    private readonly email: string;
    private readonly passwordHash: string;

    static fromObject(userData: {id: string, email: string, passwordHash: string}) {
        return new this(
            userData["id"],
            userData["email"],
            userData["passwordHash"]
        );
    }

    constructor(id: string, email: string, passwordHash: string) {
        this.id = id;
        this.email = email;
        this.passwordHash = passwordHash;
    }

    getID(): string {
        return this.id;
    }

    getEmail(): string {
        return this.email;
    }

    getPasswordHash(): string {
        return this.passwordHash;
    }
}