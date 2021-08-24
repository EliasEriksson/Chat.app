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
echo (new DateTime("tomorrow"))->format("Y:m:d H:i:s") . "<br>";
?>
</body>
</html>