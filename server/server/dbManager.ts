import {
    DataTypes, Database, Model, MySQLConnector
} from 'https://deno.land/x/denodb/mod.ts';

export class DbManager {
    private connection: MySQLConnector;

    constructor() {
        this.connection = new MySQLConnector({
            host: "",
            username: "",
            password: "",
            database: "chatapp"
        });
    }
}