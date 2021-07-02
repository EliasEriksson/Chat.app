<?php
session_start();
error_reporting(-1);
ini_set("display_errors", 1);


include_once __DIR__ . "/../classes/forms/userRegisterForm.php";
include_once __DIR__ . "/../functions/url.php";


$userRegisterForm = new UserRegisterForm("general");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userRegisterForm->validateForm()) {
        redirect("/");
    }
} ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $userRegisterForm->toHTML()?>
<?php
echo $_SESSION;
?>
</body>
</html>
