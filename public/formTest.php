<?php
include_once __DIR__ . "/classes/forms/testForm.php";

$form = new TestForm("general");

$result = "";
if ($form->validateForm()) {
    $result = "success!";
} ?>


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
<?= $form->toHTML() ?>
<p><?= $result ?></p>

</body>
</html>