<?php

include_once __DIR__ . "/field.php";

class HiddenField extends Field {

    public function toHTML(): string
    {
        $class = $this->prefixClass("$this->name-hidden-input");
        return "<input class='$class' type='hidden' name='$this->name' value='$this->value'>";
    }
}
