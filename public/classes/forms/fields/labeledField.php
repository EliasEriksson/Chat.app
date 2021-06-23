<?php

abstract class LabeledField extends Field {
    protected string $labelText;

    public function __construct(
        string $labelText,
        string $name,
        string $value = "",
        string $classPrefix = "",
        bool $refillOnFailedPost = true,
        bool $mustValidate = true
    )
    {
        parent::__construct($name, $value, $classPrefix, $refillOnFailedPost, $mustValidate);
        $this->labelText = $labelText;
    }

    protected function wrapWithLabel(string $htmlNode): string {
        $prefixedLabelClass = $this->prefixClass("label");
        return "<label class='$prefixedLabelClass'>$htmlNode</label>";
    }
}