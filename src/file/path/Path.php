<?php

namespace devatmaliance\file_service\file\path;

class Path
{
    private PathName $name;
    private PathExtension $extension;
    private PathScheme $scheme;
    private PathHost $host;
    private PathDirectory $directory;

    public function __construct(PathScheme $scheme, PathHost $host, PathDirectory $directory, PathName $name, PathExtension $extension)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->directory = $directory;
        $this->name = $name;
        $this->extension = $extension;
    }

    public static function fromPath(string $path): self
    {
        $scheme = PathScheme::fromPath($path);
        $host = PathHost::fromPath($path);
        $directory = PathDirectory::fromPath($path);
        $name = PathName::fromPath($path);
        $extension = PathExtension::fromPath($path);

        return new self($scheme, $host, $directory, $name, $extension);
    }

    public function getScheme(): PathScheme
    {
        return $this->scheme;
    }

    public function getHost(): PathHost
    {
        return $this->host;
    }

    public function getDirectory(): PathDirectory
    {
        return $this->directory;
    }

    public function getName(): PathName
    {
        return $this->name;
    }

    public function getExtension(): PathExtension
    {
        return $this->extension;
    }

    public function get(): string
    {
        return "{$this->directory->get()}/{$this->name->get()}.{$this->extension->get()}";
    }
}