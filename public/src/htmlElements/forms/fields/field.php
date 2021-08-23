<?php

include_once __DIR__ . "/../../htmlElement.php";

abstract class Field extends HTMLElement
{
    protected string $name;
    protected string $value;
    protected bool $refillOnFailedPost;
    protected bool $mustValidate;
    protected string $id;

    public function __construct(
        string $name,
        string $value = "",
        string $id = "",
        bool $refillOnFailedPost = true,
        bool $mustValidate = true
    )
    {
        parent::__construct();
        $this->name = $name;
        $this->value = $value;
        $this->id = $id;
        $this->refillOnFailedPost = $refillOnFailedPost;
        $this->mustValidate = $mustValidate;
    }

    protected function wrapWithSpan(string $htmlNode): string
    {
        $prefixedSpanClass = $this->prefixClass($this->name . "-text");
        return "<span class='$prefixedSpanClass'>$htmlNode</span>";
    }

    public function validateField(): string
    {
        if ($this->mustValidate) {
            if (!isset($_POST[$this->name])) {
                return ucfirst("$this->name is missing in your request please resubmit.");
            } elseif (!($_POST[$this->name])) {
                return ucfirst("$this->name can not be left empty. Please resubmit with this field filled.");
            }
        }
        return "";
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function getRefillOnFailedPost(): bool
    {
        return $this->refillOnFailedPost;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function refillValue(): void
    {
        if ($this->refillOnFailedPost) {
            if (isset($_POST[$this->name])) {
                $this->value = $_POST[$this->name];
            }
        }
    }
}