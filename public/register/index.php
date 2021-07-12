<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/../src/session.php";
include_once __DIR__ . "/../src/forms/userRegisterForm.php";
include_once __DIR__ . "/../src/url.php";
session_start();

if (userLoggedIn()) {
    if (userProfileLoggedIn()) {
        redirect("..");
    }
    redirect("./profile");
}

$userRegisterForm = new UserRegisterForm("general");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userRegisterForm->validateForm()) {
        redirect(".");
    }
} ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
<?= $userRegisterForm->toHTML()?>
</body>
</html>
