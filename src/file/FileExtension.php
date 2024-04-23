<?php

namespace devatmaliance\file_service\file;

use devatmaliance\file_service\utility\FileUtility;

class FileExtension
{
    private string $extension;

    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getExtension($path));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->extension;
    }
}