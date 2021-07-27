<?php

include_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../../src/orm/dbManager.php";
include_once __DIR__ . "/../../src/forms/roomJoinForm.php";
include_once __DIR__ . "/../../src/session.php";
include_once __DIR__ . "/../../src/url.php";


requireUserProfileLogin();
$user = getSessionUser();
$dbManager = new DbManager();
$roomID = getPageParameter("../create/");

if ($room = $dbManager->getRoom($roomID)) {
    echo var_dump($room) . "<br>";
    if ($dbManager->isMember($user, $room)) {
        redirect("../?$roomID");
    }
} else {
    redirect("../create/");
}

$roomJoinForm = new RoomJoinForm($room);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($roomJoinForm->validateForm()) {
        redirect("../?$roomID");
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
<?= $roomJoinForm->toHTML() ?>
</body>
</html>
