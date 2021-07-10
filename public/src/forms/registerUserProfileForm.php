<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/fileField.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/../orm/models/userProfile.php";


class RegisterUserProfileForm extends Form
{
    private User $user;

    public function __construct(User|UserProfile $user, string $classPrefix = "")
    {
        parent::__construct([
            new TextField("Username:", "username"),
            new FileField("Avatar:", "avatar", $user, mustValidate: false)
        ], new SubmitField("register-user-profile", "Submit"), $classPrefix);
        $this->user = $user;
    }

    public function validateForm(DbManager $dbManager = null): ?object
    {
        if (!$this->validateFields()) {
            return null;
        }
        # TODO default avatar (deal with mustValidate: false)

        // since this is the creation of the profile there should only be one file in in the directory
        // for this users specific uploads
        $relativeUserDirectory = "media/users/" . $this->user->getID();

        $userUploadedFiles = scandir($relativeUserDirectory);
        foreach ([".", ".."] as $file) {
            if (!($index = array_search($file, $userUploadedFiles))) {
                array_splice($userUploadedFiles, $index, 1);
            }
        }

        $relativeFilePath = "$relativeUserDirectory" . "/" . $userUploadedFiles[0];

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