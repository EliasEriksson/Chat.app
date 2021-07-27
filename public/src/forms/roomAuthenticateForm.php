<?php

include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/privateRoom.php";
include_once __DIR__ . "/../orm/models/publicRoom.php";
include_once __DIR__ . "/fields/passwordField.php";
include_once __DIR__ . "/fields/submitField.php";


class RoomAuthenticateForm extends Form
{
    private PrivateRoom $room;

    public function __construct(PrivateRoom $room, $classPrefix = "")
    {
        $roomName = $room->getName();
        $this->room = $room;

        parent::__construct([
            new PasswordField("$roomName's password", "password")
        ], new SubmitField("authenticate-submit", "Authenticate"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): bool
    {
        $user = getSessionUser();

        if (!$this->validateFields()) {
            return false;
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($this->room->authenticate($_POST["password"])){
            return $dbManager->createMembership($this->room, $user);
        }

        return false;
    }
}