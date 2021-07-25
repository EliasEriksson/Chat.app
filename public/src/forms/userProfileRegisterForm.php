<?php
include_once __DIR__ . "/../files.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/fileField.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/../orm/models/userProfile.php";
include_once __DIR__ . "/../mime.php";

class UserProfileRegisterForm extends Form
{
    private User $user;

    public function __construct(User|UserProfile $user, string $classPrefix = "")
    {
        parent::__construct([
            new TextField("Username:", "username"),
            new FileField("Avatar:", "avatar", $user, MIME::PNG|MIME::JPEG|MIME::SVG, mustValidate: false)
        ], new SubmitField("register-user-profile", "Submit"), $classPrefix);
        $this->user = $user;
    }

    public function validateForm(DbManager $dbManager = null): ?object
    {
        if (!$this->validateFields()) {
            return null;
        }

        if (!($relativeFilePath = getLatestUploadedFile($this->user->getID()))) {
            $relativeFilePath = "media/assets/defaultAvatar.png";
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }
        if ($userProfile = $dbManager->createUserProfile($this->user, $_POST["username"], $relativeFilePath)) {
            $_SESSION["userProfile"] = $userProfile;
            return $userProfile;
        }
        return null;
    }
}