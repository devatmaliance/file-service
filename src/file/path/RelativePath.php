<?php

namespace devatmaliance\file_service\file\path;


class RelativePath
{
    private PathName $name;
    private PathExtension $extension;
    private PathDirectory $directory;

    public function __construct(PathDirectory $directory, PathName $name, PathExtension $extension)
    {
        $this->directory = $directory;
        $this->name = $name;
        $this->extension = $extension;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        $directory = PathDirectory::fromPath($path);
        $name = PathName::fromPath($path);
        $extension = PathExtension::fromPath($path);

        return new self($directory, $name, $extension);
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

    /**
     * @return string
     */
    public function get(): string
    {
        $directory = $this->directory->get();
        $filename = $this->name->get() . '.' . $this->extension->get();

        if (!empty($directory)) {
            return $directory . '/' . $filename;
        } else {
            return $filename;
        }
    }
}