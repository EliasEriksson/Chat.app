<?php

include_once __DIR__ . "/labeledField.php";


class TextareaField extends LabeledField {
    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-textarea-input");

        if ($this->id) {
            $html .= "<textarea id='$this->id' class='$class' name='$this->name'>$this->value</textarea>";
        } else {
            $html .= "<textarea class='$class' name='$this->name'>$this->value</textarea>";
        }
        return $this->wrapWithLabel($html);
    }
}