<?php

error_reporting(-1);
ini_set("display_errors", 1);


include_once __DIR__ . "/../src/forms/userLoginForm.php";
include_once __DIR__ . "/../functions/url.php";
session_start();

$userLoginForm = new UserLoginForm("general");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userLoginForm->validateForm()) {
        redirect("/");
    }
} ?>

<!doctype html>
<html lang="1">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $userLoginForm->toHTML() ?>
<?= var_dump($_SESSION) ?>

</body>
</html>


