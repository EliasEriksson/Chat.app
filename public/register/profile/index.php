<?php

include_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../../src/url.php";
include_once __DIR__ . "/../../src/session.php";
include_once __DIR__ . "/../../src/forms/userProfileRegisterForm.php";


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

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Profile</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<?= $userProfileRegisterForm->toHTML() ?>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>