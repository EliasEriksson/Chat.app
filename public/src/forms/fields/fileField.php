<?php

include_once __DIR__ . "/labeledField.php";
include_once __DIR__ . "/../../orm/models/user.php";
include_once __DIR__ . "/../../orm/models/userProfile.php";
include_once __DIR__ . "/../../../src/uuid.php";
include_once __DIR__ . "/../../mime.php";





class FileField extends LabeledField
{
    private string $relativeUploadDirectory;
    private int $allowedMIME;

    public function __construct(
        string $labelText,
        string $name,
        User|UserProfile $user,
        int $allowedMIME,
        bool $mustValidate = true
    )
    {
        parent::__construct($labelText, $name, "", false, $mustValidate);
        $this->relativeUploadDirectory = "/media/users/" . $user->getID();
        $this->allowedMIME = $allowedMIME;
    }

    private function moveUploadedFile(string $tmpFileName, string $newFileName): void
    {
        if (!is_dir($_SERVER["DOCUMENT_ROOT"] . $this->relativeUploadDirectory)) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . $this->relativeUploadDirectory, 0775, true);
        }
        $relativeFilePath = "$this->relativeUploadDirectory/$newFileName";
        $localFilePath = $_SERVER["DOCUMENT_ROOT"] . "/$relativeFilePath";
        move_uploaded_file($tmpFileName, $localFilePath);
        chmod($localFilePath, 775);
    }

    public function validateField(): string
    {
        if ($this->mustValidate) {
            if (!isset($_FILES[$this->name])) {
                return ucfirst("$this->labelText is missing in your request please resubmit.");
            } elseif (!($_FILES[$this->name])) {
                return ucfirst("$this->labelText can not be left empty.");
            }
        }

        $this->moveUploadedFile($_FILES[$this->name]["tmp_name"], uuid());
        return "";
    }


    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-file-input");

        $html .= "<input class='$class' type='file' name='$this->name'>";
        return $this->wrapWithLabel($html);
    }
}