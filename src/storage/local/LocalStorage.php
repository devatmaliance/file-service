<?php

namespace devatmaliance\file_service\storage\local;

use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\Content;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\MimeType;
use devatmaliance\file_service\file\path\Path;
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

    public function write(File $file): Path
    {
        $currentPath = $file->getPath()->get();
        $directoryPath = FileUtility::concatenatePaths($this->storagePath, pathinfo($currentPath, PATHINFO_DIRNAME));

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true) && !is_dir($directoryPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directoryPath));
            }
        }

        $filePath = FileUtility::concatenatePaths($this->storagePath, $currentPath);
        $content = $file->getContent()->get();

        if (file_put_contents($filePath, $content) === false) {
            throw new \Exception('file_put_contents ' . $filePath);
        }

        return Path::fromPath(FileUtility::concatenatePaths($this->url, $currentPath));
    }

    public function read(Path $path): File
    {
        $content = file_get_contents($path->get());
        if (!is_string($content)) {
            throw new FileReadException('Failed to read file: ' . $path->get());
        }

        return new File(new Content($content), Path::fromPath($path->get()), MimeType::fromPath($path->get()));
    }

    public function checkAvailability(File $file): bool
    {
        return true;
    }
}