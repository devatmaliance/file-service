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
        return new self(FileUtility::getMimeType($path));
    }

    public function get(): string
    {
        return $this->mimeType;
    }
}