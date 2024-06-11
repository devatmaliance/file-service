<?php

namespace devatmaliance\file_service\file;

use devatmaliance\file_service\utility\FileUtility;

class FileLocation
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = ltrim($location, '/');
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getLocation($path));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->location;
    }
}