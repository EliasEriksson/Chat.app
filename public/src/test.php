<?php


error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/mime.php";

echo MIME\PNG | MIME\JPG;