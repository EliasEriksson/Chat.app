<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/url.php";
include_once __DIR__ . "/../src/orm/dbManager.php";
include_once __DIR__ . "/../src/session.php";

if ($user = getSessionUser()) {
    $dbManager = new DbManager();
    $dbManager->deleteSession($user);
    session_destroy();
}

redirect("/");
