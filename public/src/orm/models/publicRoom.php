<?php

include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/room.php";


class PublicRoom extends Room
{
    private string $id;
    private string $name;

    public static function fromAssoc(array $roomData): PublicRoom
    {
        return new PublicRoom(
            $roomData["id"],
            $roomData["name"],
        );
    }

    public function __construct(string $id, string $name)
    {
        parent::__construct($id, $name);
    }


    public function getID(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function havePassword(): bool
    {
        return false;
    }
}