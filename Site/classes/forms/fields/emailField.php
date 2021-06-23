<?php

include_once __DIR__ . "/labeledField.php";


class EmailField extends LabeledField {
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-email-input");

        $html .= "<input class='$class' type='email' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}