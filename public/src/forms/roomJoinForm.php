<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/privateRoom.php";
include_once __DIR__ . "/../orm/models/publicRoom.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/submitField.php";


class RoomJoinForm extends Form {
    private Room $room;
    public function __construct(Room $room, string $classPrefix = "", string $method = "POST")
    {
        $roomName = $room->getName();
        $this->room = $room;
        parent::__construct([
        ], new SubmitField("room-join-submit", "Join $roomName"));
    }

    public function validateForm(): ?object
    {
        if (!$this->validateFields()) {
            return null;
        }
        echo "clicked" . "<br>";
        return null;
    }
}