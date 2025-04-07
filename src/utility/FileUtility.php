<?php

namespace devatmaliance\file_service\utility;

use League\MimeTypeDetection\FinfoMimeTypeDetector;

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

    public static function getDirectory(string $path): string
    {
        $dirname = dirname(parse_url($path, PHP_URL_PATH));
        return $dirname === '.' ? '' : $dirname;
    }

    public static function getScheme(string $path): string
    {
        return (string)parse_url($path, PHP_URL_SCHEME);
    }

    public static function getHost(string $path): string
    {
        return (string)parse_url($path, PHP_URL_HOST);
    }

    public static function getPort(string $path): ?int
    {
        return parse_url($path, PHP_URL_PORT);
    }

    public static function getMimeTypeByPath(string $path): string
    {
        return (new FinfoMimeTypeDetector('', new ExtendedExtensionToMimeTypeMap()))->detectMimeTypeFromPath($path);
    }

    public static function getMimeTypeByContent(string $content): string
    {
        return mime_content_type($content);
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

    public static function concatenatePaths(string $base, string $path): string
    {
        return sprintf('%s/%s', rtrim($base, '/'), ltrim($path, '/'));
    }

    public static function isWebUrl(string $path): bool
    {
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }
}
