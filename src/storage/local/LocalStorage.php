<?php

namespace devatmaliance\file_service\storage\local;

use devatmaliance\file_service\exception\FileNotFoundException;
use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FileContent;
use devatmaliance\file_service\file\FileMimeType;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\storage\Storage;
use devatmaliance\file_service\utility\FileUtility;

class LocalStorage implements Storage
{
    private string $url;
    private string $storagePath;

    public function __construct(string $url, string $storagePath)
    {
        $this->storagePath = $storagePath;
        $this->url = $url;
    }

    public function write(File $file): FilePath
    {
        $currentPath = $file->getPath();
        $directoryPath = FileUtility::concatenatePaths($this->storagePath, pathinfo($currentPath, PATHINFO_DIRNAME));

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true) && !is_dir($directoryPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directoryPath));
            }
        }

        $filePath = FileUtility::concatenatePaths($this->storagePath, $currentPath);
        $content = $file->getContent();

        if (file_put_contents($filePath, $content) === false) {
            throw new \Exception('file_put_contents ' . $filePath);
        }

        return FilePath::fromPath(FileUtility::concatenatePaths($this->url, $currentPath));
    }

    public function read(FilePath $path): File
    {
        $filePath = FileUtility::concatenatePaths($this->url, $path->get());
        if (!file_exists($filePath)) {
            throw new FileNotFoundException('File not found: ' . $filePath);
        }

        $content = @file_get_contents($filePath);
        if (!is_string($content)) {
            throw new FileReadException('Failed to read file: ' . $filePath);
        }

        return new File(new FileContent($content), FilePath::fromPath($filePath), FileMimeType::fromPath($filePath));
    }

    public function checkAvailability(): bool
    {
        return true;
    }
}