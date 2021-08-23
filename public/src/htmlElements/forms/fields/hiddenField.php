<?php

include_once __DIR__ . "/field.php";

class HiddenField extends Field {

    public function toHTML(): string
    {
        $class = $this->prefixClass("$this->name-hidden-input");
        if ($this->id) {
            return "<input id='$this->id' class='$class' type='hidden' name='$this->name' value='$this->value'>";
        }
        return "<input class='$class' type='hidden' name='$this->name' value='$this->value'>";
    }
}
