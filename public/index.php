<?php
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/src/url.php";
include_once __DIR__ . "/src/session.php";

if (userProfileLoggedIn()) {
    redirect("/room");
} else if (userLoggedIn()) {
    redirect("/register/profile");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "$rootURL/includes/head.php" ?>
    <title>Home</title>
</head>
<body>
<?php include("./includes/header.php") ?>
<main>
    <div class="hero">
        <h1>
            Totally the best chat client in the world
        </h1>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam fugiat tempora eos facere obcaecati,
            dolorem suscipit? Sunt maiores commodi alias mollitia voluptates id quis cumque, modi eaque
            accusamus sequi! Odio ad voluptates et sunt rem, repudiandae nulla eos officia eligendi accusantium
            ea nemo dolorem quasi fugiat deserunt? A, quisquam quaerat.</p>

        <a href="/register" class="button button-outline">get started!</a>
    </div>
    <div id="main-container">
        <div id="app" style="display: none;"></div>
    </div>
</main>
<?php include("./includes/footer.php") ?>
</body>
</html>