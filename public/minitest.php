<?php

$dir = "media/users/6e6f0bd1-e09b-11eb-8dc8-d414e339718a";

$content = scandir($dir);
foreach ([".", ".."] as $key) {
    if (!($index = array_search($key, $content))) {
        array_splice($content, $index, 1);
    }
}

var_dump($content);

