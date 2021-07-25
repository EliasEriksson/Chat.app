<?php

include_once __DIR__ . "/../dbManager.php";


class PublicRoom
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
        $this->id = $id;
        $this->name = $name;
    }


    public function getID(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}