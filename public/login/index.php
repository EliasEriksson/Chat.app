<?php
error_reporting(-1);
ini_set("display_errors", 1);

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/forms/userLoginForm.php";
include_once __DIR__ . "/../src/url.php";
include_once __DIR__ . "/../src/session.php";

if (userLoggedIn()) {
    if (userProfileLoggedIn()) {
        redirect("..");
    }
    redirect("../register/profile");
}

$userLoginForm = new UserLoginForm("general");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userLoginForm->validateForm()) {
        redirect(".");
    }
} ?>

<?php include( __DIR__ . "/../includes/header.php")?>
<?= $userLoginForm->toHTML() ?>
</body>
</html>
