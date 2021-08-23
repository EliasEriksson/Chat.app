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

$userLoginForm = new UserLoginForm();
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

<section class="hero">
    <h1>Login to your account</h1>
<?= $userLoginForm->toHTML() ?>

<p><a href="register">Click here to create an account!</a></p>
</section>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
