<?php

namespace devatmaliance\file_service\file;

use devatmaliance\file_service\utility\FileUtility;

class FileMimeType
{
    private string $mimeType;

    public function __construct(string $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getMimeTypeByPath($path));
    }

    public static function fromContent(string $content): self
    {
        return new self(FileUtility::getMimeTypeByContent($content));
    }

    public function get(): string
    {
        return $this->mimeType;
    }
}