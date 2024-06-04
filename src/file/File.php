<?php

namespace devatmaliance\file_service\file;

class File
{
    private FileContent $content;
    private FilePath $path;
    private FileMimeType $mimeType;

    public function __construct(FileContent $content, FilePath $path, FileMimeType $mimeType)
    {
        $this->content = $content;
        $this->path = $path;
        $this->mimeType = $mimeType;
    }

    public static function fromPath(string $path): File
    {
        $filePath = FilePath::fromPath($path);
        $fileContent = FileContent::fromPath($path);
        $fileMimeType = FileMimeType::fromPath($path);

        return new File($fileContent, $filePath, $fileMimeType);
    }

    public function getPath(): FilePath
    {
        return $this->path;
    }

    public function getContent(): FileContent
    {
        return $this->content;
    }

    public function getMimeType(): FileMimeType
    {
        return $this->mimeType;
    }
}