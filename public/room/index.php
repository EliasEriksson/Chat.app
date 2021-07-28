<?php

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/orm/dbManager.php";
include_once __DIR__ . "/../src/orm/models/publicRoom.php";
include_once __DIR__ . "/../src/session.php";
include_once __DIR__ . "/../src/url.php";

requireUserProfileLogin();
$user = getSessionUser();
$roomID = getPageParameter("/room/create/");
$dbManager = new DbManager();

if (!($room = $dbManager->getRoom($roomID))) {
    redirect("/room/create/");
}

if (!$dbManager->isMember($user, $room)) {
    redirect("/room/join/?$roomID");
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
<textarea id="chat-box"></textarea>
<button id="chat-send"></button>
<section id="chat-feed"></section>

<script id="client-module"
        src="/script/client/main.js"
        type="module"
        data-roomID="<?= $roomID ?>"
        data-chatFeedElementID="chat-feed"
        data-chatBoxElementID="chat-box"
        data-chatSendElementID="chat-send">
</script>
</body>
</html>
