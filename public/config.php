<?php
error_reporting(-1);
ini_set("display_errors", 1);
include_once __DIR__ . "/src/orm/models/user.php";
include_once __DIR__ . "/src/orm/models/userProfile.php";
$rootURL = $_SERVER["DOCUMENT_ROOT"] . "/";
session_start();