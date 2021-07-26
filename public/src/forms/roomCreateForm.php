<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/privateRoom.php";
include_once __DIR__ . "/../orm/models/publicRoom.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/radioField.php";
include_once __DIR__ . "/fields/passwordField.php";


class RoomCreateForm extends Form
{
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new TextField("Chat room name:", "name"),
            [
                new RadioField("Public room:", "type", "public"),
                new RadioField("Private room:", "type", "private")
            ],
            new PasswordField("Room password:", "password1", mustValidate: false),
            new PasswordField("Retype password:", "password2", mustValidate: false)
        ], new SubmitField("room-submit", "Create"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): PrivateRoom|PublicRoom|null
    {
        if (!$this->validateFields()) {
            return null;
        }
        if ($_POST["type"] === "private") {
            if (isset($_POST["password1"]) && isset($_POST["password2"])) {
                $password = $_POST["password1"];
            } else {
                $this->setError("no passwords.");
                return null;
            }
        } else {
            $password = null;
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($room = $dbManager->createRoom($_POST["name"], $password)) {
            return $room;
        }
        return null;
    }
}