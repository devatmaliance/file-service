<?php

namespace devatmaliance\file_service\utility;

use League\MimeTypeDetection\ExtensionToMimeTypeMap;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;

class ExtendedExtensionToMimeTypeMap implements ExtensionToMimeTypeMap
{
    public const MIME_TYPES_FOR_EXTENSIONS = [
        'dng' => 'image/x-adobe-dng',
        'jfif' => 'image/jpeg',
    ];

    public function lookupMimeType(string $extension): ?string
    {
        $map = array_merge(GeneratedExtensionToMimeTypeMap::MIME_TYPES_FOR_EXTENSIONS, self::MIME_TYPES_FOR_EXTENSIONS);
        return $map[$extension] ?? null;
    }
}
