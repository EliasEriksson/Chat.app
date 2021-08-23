import {
    User
} from "./user.ts";

import {
    Room
} from "./room.ts";


export class Message {
    private readonly id: string;
    private readonly user: User;
    private readonly room: Room;
    private readonly postDate: number;
    private readonly content: string;

    constructor(id: string, user: User, room: Room, postDate: number, content: string) {
        this.id = id;
        this.user = user;
        this.room = room;
        this.postDate = postDate;
        this.content = content;
    }

    getID = (): string => {
        return this.id;
    }

    getUser = (): User => {
        return this.user;
    }

    getRoom = (): Room => {
        return this.room;
    }

    getPostDate = (): number => {
        return this.postDate;
    }

    getContent = (): string => {
        return this.content;
    }

}