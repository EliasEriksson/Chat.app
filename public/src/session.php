<?php

include_once __DIR__ . "/../src/orm/models/user.php";
include_once __DIR__ . "/../src/orm/models/userProfile.php";
include_once __DIR__ . "/url.php";


function requireUserLogin(string $redirect = "/login/"): void
{
        if (!isset($_SESSION["user"])) {
        redirect($redirect);
    }
}


function getSessionUser(string $redirect = "/login/"): User
{
    requireUserLogin($redirect);
    return $_SESSION["user"];
}


function requireUserProfileLogin(): void
{
    requireUserLogin();
    # TODO implement the rest after config file is done
}

function getSessionUserProfile(): UserProfile
{
    requireUserProfileLogin();
    return $_SESSION["userProfile"];
}

function userLoggedIn(): bool
{
    if (isset($_SESSION["user"])) {
        return true;
    }
    return false;
}

function userProfileLoggedIn(): bool
{
    if (isset($_SESSION["userProfile"])) {
        return true;
    }
    return false;
}