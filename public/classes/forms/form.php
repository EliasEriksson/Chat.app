<?php

include_once __DIR__ . "/fields/htmlElement.php";
include_once __DIR__ . "/fields/submitField.php";


abstract class Form extends HTMLElement
{
    protected array $fields;
    private string $error;
    private bool $userError;
    private SubmitField $submit;

    public function __construct(array $fields, SubmitField $submit, $classPrefix = "")
    {
        foreach ($fields as $field) {
            if ($field->getRefillOnFailedPost()) {
                if (isset($_POST[$field->getName()])) {
                    $field->setValue($_POST[$field->getName()]);
                }
            }
        }

        parent::__construct($classPrefix);
        $this->fields = $fields;
        $this->submit = $submit;
        $this->error = "";
        $this->userError = false;
    }

    public abstract function validateForm(): ?object;

    protected function validateFields(): bool
    {
        foreach ([$this->submit, ...$this->fields] as $field) {
            if ($error = $field->validateField()) {
                $this->setError($error);
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $error
     * @param bool $userError
     */
    protected function setError(string $error, $userError = true): void
    {
        $this->error = $error;
        $this->userError = $userError;
    }
}