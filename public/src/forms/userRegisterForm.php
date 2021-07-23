<?php

include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/fields/emailField.php";
include_once __DIR__ . "/fields/passwordField.php";
include_once __DIR__ . "/fields/submitField.php";



class UserRegisterForm extends Form
{
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new EmailField("Email:", "email"),
            new PasswordField("Password:", "password1", refillOnFailedPost: false),
            new PasswordField("Repeat Password:", "password2", refillOnFailedPost: false)
        ], new SubmitField("register", "Register"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): ?User
    {
        if (!$this->validateFields()) {
            return null;
        }
        if ($_POST["password1"] !== $_POST["password2"]) {
            $this->setError("The passwords does not match.");
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($user = $dbManager->createUser($_POST["email"], $_POST["password1"])) {
            $dbManager->updateSession($user);
            $_SESSION["user"] = $user;
            return $user;
        }

        $this->setError("That email is already in use.");
        return null;
    }
}