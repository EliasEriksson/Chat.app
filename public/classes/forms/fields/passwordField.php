<?php

include_once __DIR__ . "/labeledField.php";


class PasswordField extends LabeledField
{
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-password-input");

        $html .= "<input class='$class' type='password' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}