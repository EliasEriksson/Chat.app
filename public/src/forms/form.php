<?php

include_once __DIR__ . "/fields/htmlElement.php";
include_once __DIR__ . "/fields/submitField.php";


abstract class Form extends HTMLElement
{
    protected array $fields;
    private string $error;
    private bool $userError;
    private SubmitField $submit;
    private string $method;

    public function __construct(
        array $fields,
        SubmitField $submit,
        string $classPrefix = "",
        string $method = "POST"
    )
    {
        parent::__construct($classPrefix);
        foreach ($fields as $field) {
            $field->refillValue();
            $field->setClassPrefix($classPrefix);
        }

        $this->fields = $fields;
        $this->submit = $submit;
        $this->method = $method;
        $this->error = "";
        $this->userError = false;
    }

    public abstract function validateForm(): ?object;

    protected function validateFields(): bool
    {
        if ($error = $this->submit->validateField()) {
            $this->setError("This was not the form that was submitted.", false);
            return false;
        }

        foreach ($this->fields as $field) {
            if ($error = $field->validateField()) {
                $this->setError($error);
                return false;
            }
        }
        return true;
    }

    protected function setError(string $error, $userError = true): void
    {
        $this->error = $error;
        $this->userError = $userError;
    }

    private function getError(): string
    {
        return trim($this->error, ":");
    }

    private function errorToHTML(): string
    {
        $class = $this->prefixClass("user-error");
        $error = $this->getError();
        return "<span class='$class'>$error</span>";
    }

    public function toHTML(): string
    {
        $class = $this->prefixClass("form");
        $enctype = "application/x-www-form-urlencoded";

        foreach ($this->fields as $field) {
            if (is_a($field, "FileField")) {
                $enctype = "multipart/form-data";
                break;
            }
        }
        $html = "<form enctype='$enctype' method='$this->method' class='$class'>";

        foreach ([...$this->fields, $this->submit] as $field) {
            $html .= $field->toHTML();
        }

        if ($this->userError) {
            $html .= $this->errorToHTML();
        }

        $html .= "</form>";
        return $html;
    }
}