<?php
include_once __DIR__ . "/../src/url.php";

session_start();
session_destroy();
redirect("/");
