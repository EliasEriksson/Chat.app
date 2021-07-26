<?php

include_once __DIR__ . "/fields/htmlElement.php";
include_once __DIR__ . "/fields/submitField.php";
include_once __DIR__ . "/../flattenArray.php";


abstract class Form extends HTMLElement
{
    protected array $fields;
    private array $flattenedFields;
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
        $this->flattenedFields = flattenArray($fields);
        foreach ($this->flattenedFields as $field) {
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

        foreach ($this->flattenedFields as $field) {
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

    private function arrayToHTML(array $elements, bool $addDiv = false): string
    {
        $html = "";
        $names = [];

        foreach ($elements as $element) {
            if (is_array($element)) {
                $html .= $this->arrayToHTML($element, true);
            } else {
                $html .= $element->toHTML();
                array_push($names, $element->getName());
            }
        }
        if ($addDiv) {
            $class = implode("-", [...$names, "wrapper"]);
            $html = "<div class='$class'>$html</div>";
        }
        return $html;
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

        $html .= $this->arrayToHTML([...$this->fields, $this->submit]);

        if ($this->userError) {
            $html .= $this->errorToHTML();
        }

        $html .= "</form>";
        return $html;
    }
}