<?php
include_once __DIR__ . "/orm/models/user.php";
include_once __DIR__ . "/orm/models/userProfile.php";

/**
 * wrapper around php default scandir() function.
 * this function removes the . and .. directory from the result.
 *
 * @param string $path
 * @return array
 */
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

/**
 * get the latest modified file in a directory.
 *
 * the returned filepath is relative to the web/filesystem root.
 *
 * @param string $userID
 * @return string
 */
function getLatestUploadedFile(string $userID): string
{
    $bestTime = 0;
    $bestFile = "";
    $dir = $_SERVER["DOCUMENT_ROOT"] . "/media/users/$userID";
    if (!file_exists($dir)) {
        mkdir($dir, 0755);
        return "";
    }
    foreach (scanDirectory($dir) as $file) {
        if (($currentTime = filemtime("$dir/$file")) > $bestTime) {
            $bestTime = $currentTime;
            $bestFile = $file;
        }
    }
    if ($bestFile) {
        return "media/users/$userID/$bestFile";
    }
    return "";
}