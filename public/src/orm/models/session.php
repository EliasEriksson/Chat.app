<?php


include_once __DIR__ . "/../dbManager.php";


class Session
{
    private string $userID;
    private string $sessionID;

    public static function fromAssoc(array $sessionData): Session
    {
        return new Session(
            $sessionData["userID"],
            $sessionData["sessionID"]
        );
    }

    public function __construct(string $userID, string $sessionID)
    {
        $this->userID = $userID;
        $this->sessionID = $sessionID;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }

    public function getSessionID(): string
    {
        return $this->sessionID;
    }
}