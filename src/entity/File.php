<?php

namespace devatmaliance\file_service\entity;

class File
{
    private FileContent $content;
    private FilePath $path;

    public function __construct(FileContent $content, FilePath $path)
    {
        $this->content = $content;
        $this->path = $path;
    }

    public static function fromPath(string $path): File
    {
        $filePath = FilePath::fromPath($path);
        $fileContent = FileContent::fromPath($path);

        return new File($fileContent, $filePath);
    }

    public function getPath(): string
    {
        return $this->path->get();
    }

    public function getContent(): string
    {
        return $this->content->get();
    }

}