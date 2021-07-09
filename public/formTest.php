<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/classes/forms/registerUserProfile.php";
include_once __DIR__ . "/functions/session.php";
include_once __DIR__ . "/classes/forms/fileUploadForm.php";


session_start();

echo "POST:"; echo var_dump($_POST)."<br><br>FILES:";
echo var_dump($_FILES)."<br><br>";

$user = getSessionUser();
$userProfileForm = new RegisterUserProfileForm($user);

$result = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($userProfileForm->validateForm()) {
        $result = true;
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $userProfileForm->toHTML()?>
<?php
if ($result) {
    echo "successfully validated";
}
?>
</body>
</html>