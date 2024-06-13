<?php

namespace devatmaliance\file_service\file\path;

use devatmaliance\file_service\utility\FileUtility;

class PathDirectory
{
    private string $directory;

    public function __construct(string $directory = '')
    {
        $directory = ltrim($directory, '/');
        $directory = rtrim($directory, '/');

        $this->directory = $directory;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getDirectory($path));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->directory;
    }
}