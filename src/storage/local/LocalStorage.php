<?php

namespace devatmaliance\file_service\storage\local;

use devatmaliance\file_service\exception\FileNotFoundException;
use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FileContent;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\storage\Storage;

class LocalStorage implements Storage
{
    public function write(File $file): FilePath
    {
        $path = $file->getPath();
        $content = $file->getContent();

        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }

        if (file_put_contents($path, $content) === false) {
            throw new \Exception('file_put_contents ' . $path);
        }

        return FilePath::fromPath($path);
    }

    public function read(FilePath $path): File
    {
        $filePath = $path->get();
        if (!file_exists($filePath)) {
            throw new FileNotFoundException('File not found: ' . $filePath);
        }

        $content = @file_get_contents($filePath);
        if (!is_string($content)) {
            throw new FileReadException('Failed to read file: ' . $filePath);
        }

        return new File(new FileContent($content), $path);
    }
}