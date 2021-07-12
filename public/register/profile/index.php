<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/../../src/url.php";
include_once __DIR__ . "/../../src/session.php";
include_once __DIR__ . "/../../src/forms/userProfileRegisterForm.php";
session_start();
var_dump($_SESSION);

if (userProfileLoggedIn()) {
    redirect("../..");
}

$user = getSessionUser();
$userProfileRegisterForm = new UserProfileRegisterForm($user);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($userProfile = $userProfileRegisterForm->validateForm()) {
        redirect(".");
    }
} ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $userProfileRegisterForm->toHTML() ?>
</body>
</html>