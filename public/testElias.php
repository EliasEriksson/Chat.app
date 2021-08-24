<?php
include_once __DIR__ . "/config.php";
?>

<!doctype html>
<html lang="en">
<head>
    <title>testElias.php</title>
</head>
<body>
<?php
$d = DateTime::createFromFormat("U", 1629807618, new DateTimeZone("Europe/Stockholm"));
echo $d->format("Y:m:d H:i") . "<br>";
?>
</body>
</html>