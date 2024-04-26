<?php

namespace devatmaliance\file_service\utility;

class FileUtility
{
    public static function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function getName(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function getLocation(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    public static function generateRandomString(int $length): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public static function criticalSymbolClean(string $dirtyText): string
    {
        $regExp = '/[^a-zа-я.0-9_-]/ui';

        return preg_replace($regExp, '', trim($dirtyText));
    }
}