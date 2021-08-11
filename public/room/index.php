<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/orm/dbManager.php";
include_once __DIR__ . "/../src/orm/models/publicRoom.php";
include_once __DIR__ . "/../src/session.php";
include_once __DIR__ . "/../src/url.php";
include_once __DIR__ . "/../src/xrender.php";

requireUserProfileLogin();
$user = getSessionUser();
$roomID = getPageParameter("/room/create/");
$dbManager = new DbManager();

if (!($room = $dbManager->getRoom($roomID))) {
    redirect("/room/create/");
}

if (!$dbManager->isMember($user, $room)) {
    redirect("/room/join/?$roomID");
} ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <script id="client-module"
            defer
            src="/script/client/main.js"
            type="module"
            data-roomID="<?= $roomID ?>"
            data-chatFeedElementID="chat-feed"
            data-chatBoxElementID="chat-box"
            data-chatSendElementID="chat-send">
    </script>
    <title>Rooms</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<textarea id="chat-box"></textarea>
<button id="chat-send">send</button>
<button id="load-history">load</button>
<section id="chat-feed">
    <?php $messages = $dbManager->getMessages($room);
    foreach ($messages as $message) {
        echo render("$rootURL/templates/message.html", $message->getAllAsAssoc());
    } ?>
</section>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
