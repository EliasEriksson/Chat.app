# Chat.app
put content in `public/` directory on webserver.

Initialize database with `sql/createDatabase.sql`.

Add a `.credentials.json` inside `server/` with following info:
```json
{
  "hostname": "mariadbHostname",
  "username": "mariadbUsername",
  "password": "mariadbPassword",
  "db": "chatapp"
}
```



Run chat server with `deno run --allow-read --allow-net main.ts` from within `server/`