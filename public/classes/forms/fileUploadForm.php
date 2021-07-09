<?php

include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/../orm/models/userProfile.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/fields/fileField.php";


class FileUploadForm extends Form
{
    public function __construct(User|UserProfile $user, string $classPrefix = "")
    {
        parent::__construct([
            new FileField("File label:", "file-name", $user),
        ], new SubmitField("file-upload-form", "submit"), $classPrefix);
    }

    public function validateForm(): ?object
    {
        echo "FiledUploadForm::validateForm()<br> ";
        if (!$this->validateFields()) {
            return null;
        }
        return $this;
    }
}