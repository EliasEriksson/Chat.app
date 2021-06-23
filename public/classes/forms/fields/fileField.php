<?php

include_once __DIR__ . "/labeledField.php";


class FileField extends LabeledField
{
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-file-input");

        $html .= "<input class='$class' type='file' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}