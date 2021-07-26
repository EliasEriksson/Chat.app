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
                new RadioField("Public room:", "type", "public", true, id: "enable-passwords"),
                new RadioField("Private room:", "type", "private", id: "disable-passwords")
            ],
            new PasswordField("Room password:", "password1", mustValidate: false),
            new PasswordField("Retype password:", "password2", mustValidate: false)
        ], new SubmitField("room-submit", "Create"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): PrivateRoom|PublicRoom|null
    {
        $user = getSessionUser();
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
        echo "got here" . "<br>";
        if ($room = $dbManager->createRoom($_POST["name"], $password)) {
            if ($dbManager->createMembership($room, $user)) {
                return $room;
            }
            // something must have gone wrong with the session.
            // dont want to create a room with no user
            // cleaning up
            $dbManager->deleteRoom($room);
        }
        return null;
    }

    public function toHTML(): string
    {
        $html = parent::toHTML();
        $html .= "<script  
                      data-enable='enable-passwords'
                      data-disable='disable-passwords'
                      data-targets='password1-password-input password2-password-input' 
                      src='/script/disableInputs.js'>
                  </script>";
        return $html;
    }
}