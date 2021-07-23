import {
    DataTypes, Database, Model, MySQLConnector, MySQLOptions
} from 'https://deno.land/x/denodb/mod.ts';

const credentials: MySQLOptions = JSON.parse(await Deno.readTextFile(".credentials.json"));
const connection = new MySQLConnector(credentials);
const db = new Database(connection);

