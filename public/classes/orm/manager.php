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
    private mysqli $dbConn;

    function __construct()
    {
        $this->dbConn = new mysqli(
            $_SERVER["DB_HOSTNAME"],
            $_SERVER["DB_USER"],
            $_SERVER["DB_PASS"],
            $_SERVER["DB_NAME"]
        );
        if ($this->dbConn->connect_errno !== 0) {
            die("Could not connect. Error: " . $this->dbConn->connect_errno . " " . $this->dbConn->connect_error);
        }
    }

    /**
     * Makes it possible to make queries towards the DB
     * Query should be prepared before method is being called
     * 
     * @param string $sql
     * @return mixed
     */
    public function query(string $sql)
    {
        return $this->dbConn->query($sql);
    }
}
