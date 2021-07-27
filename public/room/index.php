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
<template>
    <div>test</div>
</template>
<section id="chat"></section>
<script src="/script/client/main.js"
        data-roomID="<?= $roomID ?>"
        data-chatFeedElementID="chat"
        data-chatBoxElementID=""
        data-chatSendElementID="">
</script>
</body>
</html>
