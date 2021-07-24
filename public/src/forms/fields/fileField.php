<?php
include_once __DIR__ . "/labeledField.php";
include_once __DIR__ . "/../../orm/models/user.php";
include_once __DIR__ . "/../../orm/models/userProfile.php";
include_once __DIR__ . "/../../../src/uuid.php";
include_once __DIR__ . "/../../mime.php";


class FileField extends LabeledField
{
    private string $relativeUploadDirectory;
    private int $allowedMIMEs;

    public function __construct(string $labelText, string $name, User|UserProfile $user, int $allowedMIMEs, bool $mustValidate = true)
    {
        parent::__construct($labelText, $name, "", false, $mustValidate);
        $this->relativeUploadDirectory = "/media/users/" . $user->getID();
        $this->allowedMIMEs = $allowedMIMEs;
    }

    private function moveUploadedFile(string $tmpFileName, string $newFileName): bool
    {
        if ($extension = MIME::mimeIsAllowed($this->allowedMIMEs, mime_content_type($tmpFileName))) {
            $newFileName .= $extension;
            $relativeFilePath = "$this->relativeUploadDirectory/$newFileName";
            $localFilePath = $_SERVER["DOCUMENT_ROOT"] . "/$relativeFilePath";

            if (!is_dir($_SERVER["DOCUMENT_ROOT"] . $this->relativeUploadDirectory)) {
                mkdir($_SERVER["DOCUMENT_ROOT"] . $this->relativeUploadDirectory, 0775, true);
            }

            move_uploaded_file($tmpFileName, $localFilePath);
            chmod($localFilePath, 775);
            return true;
        }
        return false;
    }

    public function validateField(): string
    {
        if ($this->mustValidate) {
            if (!isset($_FILES[$this->name])) {
                return ucfirst($this->getTrimmedLabel()) . " is missing in your request please resubmit.";
            } elseif (!($_FILES[$this->name])) {
                return ucfirst($this->getTrimmedLabel()) . " can not be left empty.";
            } else if (!$this->moveUploadedFile($_FILES[$this->name]["tmp_name"], uuid())) {
                return ucfirst("File uploaded as " . lcfirst($this->getTrimmedLabel()) . " is not an allowed file type");
            }
        }
        return "";
    }

    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-file-input");
        $acceptedMimes = MIME::acceptedMimes($this->allowedMIMEs);

        $html .= "<input class='$class' type='file' name='$this->name' accept='$acceptedMimes'>";
        return $this->wrapWithLabel($html);
    }
}