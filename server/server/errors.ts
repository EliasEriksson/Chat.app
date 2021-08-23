class ChatError extends Error {
    constructor(message: string) {
        super(message);
        this.name = this.constructor.name;
    }
}


export class UnauthorizedError extends ChatError {}


export class ConnectionAborted extends ChatError {}
