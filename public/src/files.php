<?php
include_once __DIR__ . "/orm/models/user.php";
include_once __DIR__ . "/orm/models/userProfile.php";

function scanDirectory(string $path): array
{
    $files = scandir($path);
    foreach ([".", ".."] as $file) {
        if (!($index = array_search($file, $files))) {
            array_splice($files, $index, 1);
        }
    }
    return $files;
}

function getLatestUploadedFile(string $userID): string
{

    $bestTime = 0;
    $bestFile = "";
    $dir = $_SERVER["DOCUMENT_ROOT"] . "/media/users/" . $userID;
    foreach (scanDirectory($dir) as $file) {
        if (filemtime($file) > $bestTime) {

        }
    }
}