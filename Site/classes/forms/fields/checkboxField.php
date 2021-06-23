<?php

include_once __DIR__ . "/labeledField.php";


class CheckboxField extends LabeledField {
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-checkbox-input");

        $html .= "<input class='$class' type='checkbox' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }

    function validateField(): string
    {
        if ($this->mustValidate) {

        }
        return "";
    }
}