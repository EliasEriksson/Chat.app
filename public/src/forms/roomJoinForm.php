<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/privateRoom.php";
include_once __DIR__ . "/../orm/models/publicRoom.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/submitField.php";


class RoomJoinForm extends Form {
    public function __construct(PrivateRoom|PublicRoom $room, string $classPrefix = "", string $method = "POST")
    {
        $roomName = $room->getName();
        parent::__construct([
        ], new SubmitField("room-join-submit", "Join $roomName"));
    }

    public function validateForm(): ?object
    {
        if (!$this->validateFields()) {
            return null;
        }
    }
}