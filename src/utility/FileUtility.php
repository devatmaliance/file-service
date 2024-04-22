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
}