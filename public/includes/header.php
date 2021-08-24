<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../src/session.php";
?>

<header class="flex">
    <a href="/index.php">chat.app</a>

    <nav>
        <?php if (userProfileLoggedIn()) { ?>
            <a class="button button-outline" href="/room">rooms</a>
            <a class="button" href="/logout">logout</a>
        <?php } else if (userLoggedIn()) { ?>
            <a class="button button-outline" href="/register/profile">Complete Profile</a>
            <a class="button" href="/logout">logout</a>
        <?php } else { ?>
            <a href="/register" class="button button-outline">Sign Up</a>
            <a href="/login" class="button">Login</a>
        <?php } ?>
    </nav>
</header>