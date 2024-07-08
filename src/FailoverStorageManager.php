<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\exception\FileNotFoundException;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\register\FileRegister;
use devatmaliance\file_service\finder\StorageCriteriaDTO;
use devatmaliance\file_service\finder\StorageFinder;
use devatmaliance\file_service\storage\BaseStorageConfiguration;
use devatmaliance\file_service\storage\Storage;
use Psr\Log\LoggerInterface;

class FailoverStorageManager implements StorageManager
{
    private StorageFinder $finder;
    private FileRegister $register;
    private LoggerInterface $logger;

    public function __construct(StorageFinder $finder, FileRegister $register, LoggerInterface $logger)
    {
        $this->finder = $finder;
        $this->register = $register;
        $this->logger = $logger;
    }

    public function write(File $file, RelativePath $aliasPath, ?StorageCriteriaDTO $criteria = null): Path
    {
        $alias = $this->register->reserveAlias($aliasPath);

        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
            $criteria->permission = BaseStorageConfiguration::READ_WRITE;
        }

        $path = $this->executeOnStorages(function (Storage $storage) use ($file) {
            return $storage->write($file);
        }, $criteria, 'fileSystem-storage');

        if (!$path) {
            throw new FileNotFoundException('Не удалось найти подходящее хранилище для записи файла.');
        }

        $this->register->registerFile($path, $alias);

        return $alias;
    }

    public function read(Path $path, ?StorageCriteriaDTO $criteria = null): File
    {
        if ($this->register->isRegisteredFile($path)) {
            $path = $this->register->get($path);
        }

        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
        }
        $criteria->baseUrl = $path->getBaseUrl()->get();

        $file = $this->executeOnStorages(function (Storage $storage) use ($path) {
            return $storage->read($path);
        }, $criteria, 'fileSystem-storage');

        if (!$file) {
            throw new FileNotFoundException('Файл не найден.');
        }

        return $file;
    }

    public function checkAvailability(File $file, ?StorageCriteriaDTO $criteria = null): array
    {
        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
        }

        $storagesState = [];
        $this->executeOnStorages(function (Storage $storage) use ($file, &$storagesState) {
            $storagesState[$storage->getConfig()->getBaseUrl()] = $storage->checkAvailability($file);
        }, $criteria, 'fileSystem-storage');

        return $storagesState;
    }

    private function executeOnStorages(callable $operation, StorageCriteriaDTO $criteria, string $logCategory)
    {
        try {
            $storages = $this->finder->find($criteria);
            foreach ($storages as $storage) {
                try {
                    return $operation($storage);
                } catch (\Throwable $exception) {
                    $this->logError($exception, $logCategory);
                }
            }
        } catch (\Throwable $exception) {
            $this->logError($exception, 'fileSystem-main');
        }

        return null;
    }

    private function logError(\Throwable $exception, string $category): void
    {
        $this->logger->error($exception->getMessage(), ["category" => $category]);
    }
}