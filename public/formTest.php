<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/src/session.php";
include_once __DIR__ . "/src/forms/registerUserProfileForm.php";
session_start();

$user = getSessionUser();
$form = new RegisterUserProfileForm($user, "general");

$success = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($form->validateForm()) {
        $success = true;
    }
}
?>


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
<?= $form->toHTML() ?>
<?php
if ($success) {
    echo "<p>Success!</p>";
} else {
    echo "<p>Failure</p>";
}
?>
</body>
</html>
