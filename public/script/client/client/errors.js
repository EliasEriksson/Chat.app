class ChatError extends Error {
    constructor(message) {
        super(message);
        this.name = this.constructor.name;
    }
}

export class UnauthorizedError extends ChatError {}