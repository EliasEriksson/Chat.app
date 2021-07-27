<?php

include_once __DIR__ . "/../../../config.php";
include_once __DIR__ . "/../../../src/orm/dbManager.php";
include_once __DIR__ . "/../../../src/forms/roomAuthenticateForm.php";
include_once __DIR__ . "/../../../src/session.php";
include_once __DIR__ . "/../../../src/url.php";

requireUserProfileLogin();
$user = getSessionUser();
$dbManager = new DbManager();
$roomID = getPageParameter("/room/create/");

if (!($room = $dbManager->getRoom($roomID))) {
    redirect("/room/create/");
}
if ($dbManager->isMember($user, $room)) {
    redirect("/room/?$roomID");
}

# TODO is there a way to design so instanceof can be avoided?
if (!($room instanceof PrivateRoom)) {
    redirect("/room/join/?$roomID");
}
$roomAuthenticateForm = new RoomAuthenticateForm($room);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($roomAuthenticateForm->validateForm($dbManager)) {
        redirect("/room/?$roomID");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $roomAuthenticateForm->toHTML() ?>
</body>
</html>