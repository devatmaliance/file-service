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
        return substr(base64_encode(random_bytes($length)), 0, $length);
    }

    public static function criticalSymbolClean(string $dirtyText): string
    {
        $regExp = '/[^a-zа-я.0-9_-]/ui';

        return preg_replace($regExp, '', trim($dirtyText));
    }
}