<?php

include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/room.php";


class PublicRoom extends Room
{
    public static function fromAssoc(array $roomData): PublicRoom
    {
        echo var_dump($roomData) . "<br>";
        return new PublicRoom(
            $roomData["id"],
            $roomData["name"],
        );
    }

    public function havePassword(): bool
    {
        return false;
    }
}