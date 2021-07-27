<?php

include_once __DIR__ . "/../dbManager.php";
include_once __DIR__ . "/room.php";


class PublicRoom extends Room
{
    public static function fromAssoc(array $roomData): PublicRoom
    {
        return new PublicRoom(
            $roomData["id"],
            $roomData["name"],
        );
    }
}