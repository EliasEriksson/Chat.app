<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/htmlElements/forms/userLoginForm.php";
include_once __DIR__ . "/../src/url.php";
include_once __DIR__ . "/../src/session.php";

if (userLoggedIn()) {
    if (userProfileLoggedIn()) {
        redirect("..");
    }
    redirect("../register/profile");
}

$userLoginForm = new UserLoginForm("general");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userLoginForm->validateForm()) {
        redirect(".");
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Login</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<?= $userLoginForm->toHTML() ?>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
