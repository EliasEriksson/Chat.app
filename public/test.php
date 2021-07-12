<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/src/session.php";
include_once __DIR__ . "/src/forms/userProfileRegisterForm.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = mime_content_type($_FILES["file"]["tmp_name"]);
    echo $_FILES["file"]["type"] . "<br>";
    echo "$type";
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="file-form">
</form>
</body>
</html>
