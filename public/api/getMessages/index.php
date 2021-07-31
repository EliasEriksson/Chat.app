<?php
include_once __DIR__ . "/../../src/orm/dbManager.php";
include_once __DIR__ . "/../../src/orm/models/room.php";
include_once __DIR__ . "/../../src/orm/models/message.php";


if (!isset($_GET["roomID"])) {
    http_response_code(400);
}

$dbManager = new DbManager();
if (!($room = $dbManager->getRoom($_GET["roomID"]))) {
    http_response_code(400);
}

if (isset($_GET["before"])) {
    $messages = $dbManager->getMessages($room, $_GET["before"]);
} else {
    $messages = $dbManager->getMessages($room);
}

$messagesAssoc = [];
foreach ($messages as $message) {
    array_push($messagesAssoc, [
        "id" => $message->getID(),
        "userID" => $message->getUser()->getID(),
        "email" => $message->getUser()->getEmail(),
        "username" => $message->getUserProfile()->getUsername(),
        "avatar" => $message->getUserProfile()->getAvatar(),
        "content" => $message->getContent(),
        "postDate" => $message->getPostDate()->getTimestamp()
    ]);
}

echo json_encode($messages, JSON_UNESCAPED_UNICODE);
