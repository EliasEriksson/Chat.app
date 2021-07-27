<?php

include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/room.php";


class PrivateRoom extends Room
{
    private string $passwordHash;

    public static function fromAssoc(array $roomData): Room
    {
        if (is_null($roomData["passwordHash"])) {
            return PublicRoom::fromAssoc($roomData);
        }
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

    public function authenticate(string $password): bool
    {
        return password_verify($password, $this->passwordHash);

    }
}