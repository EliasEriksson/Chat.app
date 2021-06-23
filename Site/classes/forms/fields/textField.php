<?php

include_once __DIR__ . "/labeledField.php";

class TextField extends LabeledField
{
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-text-input");

        $html .= "<input class='$class' type='text' name='$this->name' value='$this->value'>";
        return $this->wrapWithLabel($html);
    }
}