<?php

namespace devatmaliance\file_service\entity;

use devatmaliance\file_service\utility\FileUtility;

class FilePath
{
    private string $location;
    private string $name;
    private string $extension;

    public function __construct(string $location, string $name, string $extension)
    {
        $this->location = $location;
        $this->name = $name;
        $this->extension = $extension;
    }

    public static function fromPath(string $path): self
    {
        $location = FileUtility::getLocation($path);
        $name = FileUtility::getName($path);
        $extension = FileUtility::getExtension($path);

        return new self($location, $name, $extension);
    }

    public function get(): string
    {
        return "{$this->location}/{$this->name}.{$this->extension}";
    }
}