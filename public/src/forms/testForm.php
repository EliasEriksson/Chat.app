<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/form.php";
include_once __DIR__ . "/fields/textField.php";
include_once __DIR__ . "/fields/submitField.php";


class TestForm extends Form
{
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new TextField("A test:", "test")
        ], new SubmitField("test-form-submit", "submit"), $classPrefix);
    }

    public function validateForm(): ?object
    {
        if (!$this->validateFields()) {
            return null;
        }

        return $this;
    }
}