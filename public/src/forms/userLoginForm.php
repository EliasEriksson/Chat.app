<?php

include_once __DIR__ . "/form.php";
include_once __DIR__ . "/../orm/dbManager.php";
include_once __DIR__ . "/../orm/models/user.php";
include_once __DIR__ . "/fields/emailField.php";
include_once __DIR__ . "/fields/passwordField.php";
include_once __DIR__ . "/fields/submitField.php";


class UserLoginForm extends Form
{
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new EmailField("Email: ", "email"),
            new PasswordField("Password:", "password")
        ], new SubmitField("login", "Log in"), $classPrefix);
    }

    public function validateForm(DbManager $dbManager = null): ?User
    {
        if (!$this->validateFields()) {
            return null;
        }

        if (!$dbManager) {
            $dbManager = new DbManager();
        }

        if ($user = $dbManager->getUserFromEmail($_POST["email"])) {
            if ($user->authenticate($_POST["password"])) {
                $dbManager->updateSession($user);
                $_SESSION["user"] = $user;
                if ($userProfile = $dbManager->getUserProfile($user)) {
                    $_SESSION["userProfile"] = $userProfile;
                }
                return $user;
            } else {
                $this->setError("Passwords Does not match.");
            }
        }

        $this->setError("No account with that email exists.");
        return null;
    }
}