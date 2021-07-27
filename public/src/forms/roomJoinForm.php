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

    public function validateForm(DbManager $dbManager = null): bool
    {
        $user = getSessionUser();

        if (!$this->validateFields()) {
            return false;
        }

        $roomID = $this->room->getID();
        # TODO is there a way to design so instanceof can be avoided?
        if ($this->room instanceof PrivateRoom) {
            redirect("/room/join/authenticate/?$roomID");
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($dbManager->createMembership($this->room, $user)) {
            return true;
        }

        return false;
    }
}