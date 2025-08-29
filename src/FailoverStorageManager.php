<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\exception\FileNotFoundException;
use devatmaliance\file_service\exception\StorageNotFoundException;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\register\FileRegister;
use devatmaliance\file_service\finder\StorageCriteriaDTO;
use devatmaliance\file_service\finder\StorageFinder;
use devatmaliance\file_service\storage\BaseStorageConfiguration;
use devatmaliance\file_service\storage\Storage;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

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

    public function write(File $file, ?StorageCriteriaDTO $criteria = null): Path
    {
        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
            $criteria->permission = BaseStorageConfiguration::READ_WRITE;
            $criteria->category = 'main';
        }

        $storages = $this->finder->find($criteria);
        if (empty($storages)) {
            throw StorageNotFoundException::withStorageCriteria($criteria);
        }

        /** @var Storage $storage */
        foreach ($storages as $storage) {
            try {
                $path = $storage->write($file);
                break;
            } catch (Throwable $exception) {
                $this->logError($exception, 'storage-write');
            }
        }

        if (empty($path)) {
            throw new RuntimeException('Failed to write file to any available storage');
        }

        return $path;
    }

    public function read(Path $path, ?StorageCriteriaDTO $criteria = null): File
    {
        try {
            if ($this->register->isRegisteredFile($path)) {
                $path = $this->register->get($path);
            }
        } catch (Throwable $e) {
            $this->logError($e, 'get-registered-path');
        }

        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
        }
        $criteria->baseUrl = $path->getBaseUrl()->get();

        $storages = $this->finder->find($criteria);
        if (empty($storages)) {
            throw StorageNotFoundException::withStorageCriteria($criteria);
        }

        /** @var Storage $storage */
        foreach ($storages as $storage) {
            try {
                $file = $storage->read($path);
                break;
            } catch (Throwable $exception) {
                $this->logError($exception, 'storage-write');
            }
        }

        if (empty($file)) {
            throw new FileNotFoundException(sprintf('File "%s" not found', $path->get()));
        }

        return $file;
    }

    public function checkAvailability(File $file, ?StorageCriteriaDTO $criteria = null): array
    {
        if (!$criteria) {
            $criteria = new StorageCriteriaDTO();
        }

        $storagesState = [];

        $storages = $this->finder->find($criteria);
        if (empty($storages)) {
            throw StorageNotFoundException::withStorageCriteria($criteria);
        }

        foreach ($storages as $storage) {
            try {
                $storagesState[$storage->getConfig()->getBaseUrl()] = $storage->checkAvailability($file);
            } catch (Throwable $exception) {
                $this->logError($exception, 'storage-checkAvailability');
            }
        }

        return $storagesState;
    }

    private function logError(Throwable $exception, string $category): void
    {
        $this->logger->error($exception->getMessage(), ["category" => $category]);
    }
}
