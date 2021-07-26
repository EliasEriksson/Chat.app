<?php

function flattenArray($array): array
{
    $return = [];
    foreach ($array as $item) {
        if (is_array($item)) {
            $return = array_merge($return, flattenArray($item));
        } else {
            array_push($return, $item);
        }
    }
    return $return;
}
