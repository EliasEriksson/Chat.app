<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/session.php";
include_once __DIR__ . "/../src/htmlElements/forms/userRegisterForm.php";
include_once __DIR__ . "/../src/url.php";

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

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Register</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<?= $userRegisterForm->toHTML()?>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
