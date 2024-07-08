<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\storage\Storage;
use Psr\Log\LoggerInterface;
use RuntimeException;

class DefaultStorageManager implements StorageManager
{
    private Storage $storage;
    private LoggerInterface $logger;

    public function __construct(Storage $storage, LoggerInterface $logger)
    {
        $this->storage = $storage;
        $this->logger = $logger;
    }

    public function write(File $file, RelativePath $aliasPath): Path
    {
        return $this->execute(function () use ($file) {
            return $this->storage->write($file);
        }, 'storage-write', 'Не удалось сохранить файл!');
    }

    public function read(Path $path): File
    {
        return $this->execute(function () use ($path) {
            return $this->storage->read($path);
        }, 'storage-read', 'Не удалось прочитать файл!');
    }

    public function checkAvailability(File $file): array
    {
        return $this->execute(function () use ($file) {
            return ['mainStorage' => $this->storage->checkAvailability($file)];
        }, 'storage-checkAvailability', 'Не удалось проверить доступность файла!');
    }

    private function execute(callable $operation, string $logCategory, string $errorMessage)
    {
        try {
            return $operation();
        } catch (\Throwable $exception) {
            $this->logError($exception, $logCategory);
            throw new RuntimeException($errorMessage);
        }
    }

    private function logError(\Throwable $exception, string $category): void
    {
        $this->logger->error($exception->getMessage(), ["category" => $category]);
    }
}