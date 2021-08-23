<?php
include_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../../src/orm/dbManager.php";
include_once __DIR__ . "/../../src/htmlElements/forms/roomCreateForm.php";
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

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Room | Create</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<section class="hero">
<?= $roomCreateForm->toHTML() ?>
</section>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
