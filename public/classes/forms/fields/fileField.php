<?php

include_once __DIR__ . "/labeledField.php";
include_once __DIR__ . "/../../orm/models/user.php";
include_once __DIR__ . "/../../orm/models/userProfile.php";


class FileField extends LabeledField
{
    private string $uploadDirectory;

    public function __construct(string $labelText, string $name, User|UserProfile $user, bool $refillOnFailedPost = true, bool $mustValidate = true)
    {
        parent::__construct($labelText, $name, "", $refillOnFailedPost, $mustValidate);
        $this->uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/assets/users/".$user->getID();

    }

    public function validateField(): string
    {
        if ($result = parent::validateField()) {
            return $result;
        }
        // TODO move the file to user uploaded files here
        return  ;
    }

    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-file-input");

        $html .= "<input class='$class' type='file' name='$this->name'>";
        return $this->wrapWithLabel($html);
    }
}