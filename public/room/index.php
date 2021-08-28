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
}
?>


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
    <script src="/script/chatFeed.js" defer></script>
    <title>Rooms</title>
</head>
<body>
<?php include "$rootURL/includes/header.php" ?>
<section class="room-user-list">
    <?php $roomUserProfiles = $dbManager->getRoomUserProfileList($room);
    foreach ($roomUserProfiles as $roomUserProfile) {
        echo render("$rootURL/templates/userList.html", $roomUserProfile->getAllAsAssoc());
    }
    ?>
</section>
<section class="chat-room">
    <div id="chat-feed-container">

        <section id="chat-feed">
            <?php $messages = $dbManager->getMessages($room);
            foreach ($messages as $message) {
                echo render("$rootURL/templates/message.html", $message->getAllAsAssoc());
            } ?>
        </section>
    </div>
    <div>
        <!-- <textarea id="chat-box"></textarea> -->

        <form action="">
            <input type="text" id="chat-box" placeholder="Message to <?= $room->getName() . "..." ?>"
                   autocomplete="off">
            <button id="chat-send" class="button" onclick="event.preventDefault()">send</button>
        </form>
        <!-- <input type="submit" value="send" id="chat-send" onclick="event.preventDefault()"> -->
    </div>
    <button id="load-history" class="button button-outline">load</button>



    <!--  moved to script/chatFeed.js  -->


</section>
<?php include "$rootURL/includes/footer.php" ?>
</body>
</html>
