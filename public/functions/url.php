<?php

/**
 * header is a bad name i like redirect more
 *
 * @param $url
 */
function redirect($url)
{
    header("location: $url");
}

/**
 * considering this url http://chat.eliaseriksson.eu/chat/?123234345
 *
 * this function will return 123234345
 *
 * @param string|null $redirect
 * @return string
 */
function getPageParameter(string $redirect = null): string
{
    if (count($_GET) > 0) {
        return array_key_first($_GET);
    }

    if ($redirect) {
        redirect($redirect);
    }

    return "";
}
