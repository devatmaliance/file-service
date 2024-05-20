<?php

namespace devatmaliance\file_service\file;

use devatmaliance\file_service\utility\FileUtility;

class FileExtension
{
    private string $extension;
    private static array $restrictedExtensions = ['tmp'];

    public function __construct(string $extension)
    {
        if (in_array($extension, self::$restrictedExtensions, true)) {
            throw new \InvalidArgumentException('File extension "' . $extension . '" cannot be used.');
        }

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