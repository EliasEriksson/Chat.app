<?php

// https://blog.podkalicki.com/bit-level-operations-bit-flags-and-bit-masks/


class MIME {
    const JPEG = 1;
    const PNG = 2;
    const GIF = 4;
    const SVG = 8;

    public static function allowedMIMEs(int $mimes): array {
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
        return $allowedMimes;
    }

    public static function mimeExtension(int $mime): string {
        return match ($mime) {
            1 => ".jpg",
            2 => ".png",
            4 => ".gif",
            8 => ".svg",
            default => "",
        };
    }
}
$mimes = MIME::allowedMIMEs(MIME::PNG|MIME::SVG);
var_dump($mimes);
