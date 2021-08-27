<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../../orm/dbManager.php";
include_once __DIR__ . "/../../orm/models/privateRoom.php";
include_once __DIR__ . "/../../orm/models/publicRoom.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/radioField.php";
include_once __DIR__ . "/fields/passwordField.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/fields/checkboxField.php";


class RoomCreateForm extends Form
{
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new TextField("Chat room name:", "name"),
            new CheckboxField("Password protected?", "type", "public", "password-protect"),
            [
                new PasswordField("Room password:", "password1", refillOnFailedPost: false),
                new PasswordField("Retype password:", "password2", refillOnFailedPost: false)
            ]
        ], new SubmitField("room-submit", "Create"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): ?Room
    {
        $user = getSessionUser();
        if (!$this->validateFields()) {
            return null;
        }
        if ($_POST["type"] === "private") {
            if ($_POST["password1"] === $_POST["password2"]) {
                $password = $_POST["password1"];
            } else {
                echo "set error" . "<br>";
                $this->setError("Passwords doesnt match.");
                return null;
            }
        } else {
            $password = null;
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($room = $dbManager->createRoom($_POST["name"], $password)) {
            if ($dbManager->createMembership($room, $user)) {
                return $room;
            }
            // something must have gone wrong with the session.
            // dont want to create a room with no user
            // cleaning up
            $dbManager->deleteRoom($room);
            return null;
        }
        return null;
    }
}