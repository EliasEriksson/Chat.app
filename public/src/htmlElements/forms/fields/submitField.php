<?php

include_once __DIR__ . "/field.php";

class SubmitField extends Field
{
    public function toHTML(): string
    {
        $class = $this->prefixClass("$this->name-submit-input");
        if ($this->id) {
            return "<input id='$this->id' class='$class' type='submit' name='$this->name' value='$this->value'>";
        }
        return "<input class='$class' type='submit' name='$this->name' value='$this->value'>";

    }
}