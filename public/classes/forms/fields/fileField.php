<?php

include_once __DIR__ . "/labeledField.php";


class FileField extends LabeledField
{
    private string $uploadDirectory;

    public function __construct(string $labelText, string $name, string $value = "", bool $refillOnFailedPost = true, bool $mustValidate = true)
    {
        parent::__construct($labelText, $name, $value, $refillOnFailedPost, $mustValidate);

    }

    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-file-input");

        $html .= "<input class='$class' type='file' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}