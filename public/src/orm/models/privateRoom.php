<?php

include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/publicPublicRoom.php";


class PrivateRoom extends PublicRoom
{
    private string $passwordHash;

    public static function fromAssoc(array $roomData): PrivateRoom {
        return new PrivateRoom(
            $roomData["id"],
            $roomData["name"],
            $roomData["passwordHash"]
        );
    }

    public function __construct(string $id, string $name, string $passwordHash)
    {
        parent::__construct($id, $name);
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}