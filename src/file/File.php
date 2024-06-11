<?php

namespace devatmaliance\file_service\file;

use devatmaliance\file_service\file\path\Path;

class File
{
    private Content $content;
    private Path $path;
    private MimeType $mimeType;

    public function __construct(Content $content, Path $path, MimeType $mimeType)
    {
        $this->content = $content;
        $this->path = $path;
        $this->mimeType = $mimeType;
    }

    public static function fromPath(string $path): File
    {
        $filePath = Path::fromPath($path);
        $fileContent = Content::fromPath($path);
        $fileMimeType = MimeType::fromPath($path);

        return new File($fileContent, $filePath, $fileMimeType);
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getMimeType(): MimeType
    {
        return $this->mimeType;
    }
}