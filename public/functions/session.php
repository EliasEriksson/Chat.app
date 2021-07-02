<?php

include_once __DIR__ . "/../classes/orm/models/user.php";
include_once __DIR__ . "/../classes/orm/models/userProfile.php";

function requireUserLogin(): void
{
    # TODO finish implementation when config file is done
    if (!isset($_SESSION["user"])) {

    }

}

function requireUserProfileLogin(): void
{
    requireUserLogin();
    # TODO implement the rest after config file is done
}

function getSessionUser(): User
{
    requireUserLogin();
    return $_SESSION["user"];
}

function getSessionUserProfile(): UserProfile
{
    requireUserProfileLogin();
    return $_SESSION["userProfile"];
}

function userLoggedIn(): bool
{

}

function userProfileLoggedIn(): bool
{

}