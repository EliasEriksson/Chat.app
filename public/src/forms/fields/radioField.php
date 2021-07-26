<?php

include_once __DIR__ . "/labeledField.php";


class RadioField extends LabeledField {
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-radio-input");

        $html .= "<input class='$class' type='radio' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}