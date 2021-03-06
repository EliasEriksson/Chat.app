export class Room {
    protected id: string;
    protected name: string;

    public static fromJSON = (roomData: { "id": string, "name": string }): Room => {
        return new Room(
            roomData["id"],
            roomData["name"]
        );
    }

    constructor(id: string, name: string) {
        this.id = id;
        this.name = name;
    }

    public getID = (): string => {
        return this.id;
    }

    public getName = (): string => {
        return this.name;
    }
}