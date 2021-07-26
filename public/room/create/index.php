<?php
include_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../../src/orm/dbManager.php";
include_once __DIR__ . "/../../src/forms/roomCreateForm.php";
include_once __DIR__ . "/../../src/session.php";
include_once __DIR__ . "/../../src/url.php";


requireUserProfileLogin();

$roomCreateForm = new RoomCreateForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($room = $roomCreateForm->validateForm()) {
        $roomID = $room->getID();
        redirect("../?$roomID");
    }
} ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?= $roomCreateForm->toHTML() ?>
</body>
</html>
