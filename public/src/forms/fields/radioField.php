<?php

include_once __DIR__ . "/labeledField.php";


class RadioField extends LabeledField
{
    private bool $checked;

    public function __construct(
        string $labelText,
        string $name,
        string $value = "",
        bool $checked = false,
        string $id = "",
        bool $refillOnFailedPost = true,
        bool $mustValidate = true
    )
    {
        parent::__construct($labelText, $name, $value, $id, $refillOnFailedPost, $mustValidate);
        $this->checked = $checked;
    }

    public function toHTML(): string
    {
        $html = $this->wrapWithSpan($this->labelText);
        $class = $this->prefixClass("$this->name-radio-input");
        if ($this->checked) {
            $checked = "checked";
        } else {
            $checked = "";
        }

        if ($this->id) {
            $html .= "<input id='$this->id' class='$class' type='radio' name='$this->name' value='$this->value' $checked>";
        } else {
            $html .= "<input class='$class' type='radio' name='$this->name' value='$this->value' $checked>";
        }
        return $this->wrapWithLabel($html);
    }
}