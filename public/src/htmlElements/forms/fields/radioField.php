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
        $this->checked = $checked;
        parent::__construct($labelText, $name, $value, $id, $refillOnFailedPost, $mustValidate);
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

    public function refillValue(): void
    {
        if ($this->refillOnFailedPost) {
            if (isset($_POST[$this->name])) {
                if ($_POST[$this->name] === $this->value) {
                    $this->checked = true;
                } else {
                    $this->checked = false;
                }
            }
        }
    }
}