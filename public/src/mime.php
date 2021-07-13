<?php

// https://blog.podkalicki.com/bit-level-operations-bit-flags-and-bit-masks/

/**
 * Class MIME
 * helps with handling which file types that are allowed to be uploaded.
 *
 * to add additional types add a const at the top of the class and correct mappings in the private methods
 * as well as in acceptedMimes()
 */
class MIME
{
    const JPEG = 1 << 0; # 1
    const PNG = 1 << 1; # 2
    const GIF = 1 << 2; # 4
    const SVG = 1 << 3; # 8

    private static function mimeStringToInt(string $mime): int
    {
        return match ($mime) {
            "image/jpeg" => MIME::JPEG,
            "image/png" => MIME::PNG,
            "image/gif" => MIME::GIF,
            "image/svg+xml" => MIME::SVG,
            default => 0,
        };
    }

    private static function mimeToExtension(int $mime): string
    {
        return match ($mime) {
            MIME::JPEG => ".jpg",
            MIME::PNG => ".png",
            MIME::GIF => ".gif",
            MIME::SVG => ".svg",
            default => "",
        };
    }

    /**
     * checks if a string mime is allowed.
     *
     * returns an empty string on failure.
     * returns a file extension on success.
     *
     * @param int $mimes
     * @param string $mime
     * @return string
     */
    public static function mimeIsAllowed(int $mimes, string $mime): string {
        $intMime = MIME::mimeStringToInt($mime);
        if (($mimes & $intMime) > 0) {
            return MIME::mimeToExtension($intMime);
        }
        return "";
    }

    /**
     * used to generate a file inputs accepted attribute.
     *
     * @param int $mimes
     * @return string
     */
    public static function acceptedMimes(int $mimes): string {
        $allowedMimes = [];
        if (($mimes & MIME::JPEG) > 0) {
            array_push($allowedMimes, "image/jpeg");
        }
        if (($mimes & MIME::PNG) > 0) {
            array_push($allowedMimes, "image/png");
        }
        if (($mimes & MIME::GIF) > 0) {
            array_push($allowedMimes, "image/gif");
        }
        if (($mimes & MIME::SVG) > 0) {
            array_push($allowedMimes, "image/svg+xml");
        }
        return implode(", ", $allowedMimes);
    }
}

function foo(int $int)
{
    # the string mime could come from mime_content_type()
    if ($extension = MIME::mimeIsAllowed($int, "image/jpeg")) {
        echo "$extension" . "<br>";
    } else {
        echo "error!" . "<br>";
    }
}

function mimeExample()
{
    # mimes are passed as arguments into some function that requires them.
    # the function (foo) checks if the extension matches the type given back from
    # mime_content_type() and gives an extension back. If no extension is given back
    # the mime is not allowed.
    foo(MIME::SVG | MIME::PNG);
    foo(MIME::JPEG | MIME::PNG);
}
