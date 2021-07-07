<?php

include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/../orm/models/userProfile.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/fields/fileField.php";


class FiledUploadForm extends Form
{
    public function __construct(User|UserProfile $user, string $classPrefix = "")
    {
        parent::__construct([
            new FileField("The label: ", "the-file", $user),
        ], new SubmitField("file-upload-form", "Submit"), $classPrefix);
    }
}