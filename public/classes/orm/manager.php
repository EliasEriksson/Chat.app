<?php

include_once __DIR__ . "/models/user.php";
include_once __DIR__ . "/models/userProfile.php";

// Database manager
// login info:
// host: $_SERVER["DB_HOSTNAME"]
// username: $_SERVER["DB_USER"]
// password: $_SERVER["DB_PASS"]
// database name: $_SERVER["DB_NAME"]

class DbManager
{
    private $dbConn;

    function __construct()
    {
        $this->dbConn = new mysqli(
            $_SERVER["DB_HOSTNAME"],
            $_SERVER["DB_USER"],
            $_SERVER["DB_PASS"],
            $_SERVER["DB_NAME"]
        );
        if ($this->connection->connect_errno !== 0) {
            die("Could not connect. Error: " . $this->connection->connect_errno . " " . $this->connection->connect_error);
        }
    }
}
