<?php

include_once __DIR__ . "/field.php";

class SubmitField extends Field
{
    public function toHTML(): string
    {
        $class = $this->prefixClass("$this->name-submit-input");
        return "<input class='$class' type='submit' name='$this->name' value='$this->value'>";
    }
}