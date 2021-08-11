<?php

include_once __DIR__ . "/../../config.php";
include_once __DIR__ . "/../../src/orm/dbManager.php";
include_once __DIR__ . "/../../src/forms/roomJoinForm.php";
include_once __DIR__ . "/../../src/session.php";
include_once __DIR__ . "/../../src/url.php";


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

$roomJoinForm = new RoomJoinForm($room);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($roomJoinForm->validateForm($dbManager)) {
        redirect("/room/?$roomID");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Room | Join</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<?= $roomJoinForm->toHTML() ?>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
