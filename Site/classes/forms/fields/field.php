<?php

include_once __DIR__ . "/htmlElement.php";

abstract class Field extends HTMLElement
{
    protected string $name;
    protected string $value;

    protected bool $refillOnFailedPost;
    protected bool $mustValidate;

    public function __construct(
        string $name,
        string $value = "",
        string $classPrefix = "",
        bool $refillOnFailedPost = true,
        bool $mustValidate = true
    )
    {
        parent::__construct($classPrefix);
        $this->name = $name;
        $this->value = $value;
        $this->refillOnFailedPost = $refillOnFailedPost;
        $this->mustValidate = $mustValidate;
    }

    protected function wrapWithSpan(string $htmlNode): string {
        $prefixedSpanClass = $this->prefixClass($this->name."-text");
        return "<span class='$prefixedSpanClass'>$htmlNode</span>";
    }

    abstract function validateField(): string;
}