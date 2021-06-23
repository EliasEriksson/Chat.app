<?php

include_once __DIR__ . "/labeledField.php";


class DateField extends LabeledField {
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-date-input");

        $html .= "<input class='$class' type='date' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}