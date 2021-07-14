<?php
include_once __DIR__ . "/config.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["get-session"])) {
        session_regenerate_id();
    } else if (isset($_POST["destroy-session"])) {
        session_destroy();
    }
    header("location: ./testElias.php");
}

echo session_id() . "<br>";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elias Test</title>
</head>
<body>
<form method="post" enctype="application/x-www-form-urlencoded">
    <input type="submit" name="get-session" value="get">
</form>
<form method="post" enctype="application/x-www-form-urlencoded">
    <input type="submit" name="destroy-session" value="destroy">
</form>
<script src="<?=$rootURL?>script/cookies.js"></script>
</body>
</html>
