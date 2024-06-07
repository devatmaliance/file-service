<?php

namespace devatmaliance\file_service\file;

class FilePath
{
    private FileLocation $location;
    private FileName $name;
    private FileExtension $extension;

    public function __construct(FileLocation $location, FileName $name, FileExtension $extension)
    {
        $this->location = $location;
        $this->name = $name;
        $this->extension = $extension;
    }

    public static function fromPath(string $path): self
    {
        $location = FileLocation::fromPath($path);
        $name = FileName::fromPath($path);
        $extension = FileExtension::fromPath($path);

        return new self($location, $name, $extension);
    }

    public function getLocation(): FileLocation
    {
        return $this->location;
    }

    public function getName(): FileName
    {
        return $this->name;
    }

    public function getExtension(): FileExtension
    {
        return $this->extension;
    }

    public function get(): string
    {
        return "{$this->location->get()}/{$this->name->get()}.{$this->extension->get()}";
    }
}