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
use RuntimeException;
use Yii;

class ComplexStorageManager implements StorageManager
{
    private StorageFinder $finder;
    private FileRegister $register;

    public function __construct(StorageFinder $finder, FileRegister $register)
    {
        $this->finder = $finder;
        $this->register = $register;
    }

    public function write(File $file, RelativePath $aliasPath, ?StorageCriteriaDTO $criteria = null): Path
    {
        try {
            $alias = $this->register->reserveAlias($aliasPath);

            if (!$criteria) {
                $criteria = new StorageCriteriaDTO();
                $criteria->permission = BaseStorageConfiguration::READ_WRITE;
            }

            $path = $this->executeWithStorages($criteria, function (Storage $storage) use ($file) {
                return $storage->write($file);
            });

            if (!$path) {
                throw new FileNotFoundException();
            }

            $this->register->registerFile($path, $alias);

            return $alias;
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), "fileSystem-write");
        }

        throw new RuntimeException('Не удалось сохранить файл!');
    }

    public function read(Path $path): File
    {
        try {
            if ($path->getBaseUrl()->getHost()->get() === $this->register->getBaseUrl()->get()) {
                $path = $this->register->get($path);
            }

            $criteria = new StorageCriteriaDTO();
            $criteria->baseUrl = $path->getBaseUrl()->get();

            $file = $this->executeWithStorages($criteria, function (Storage $storage) use ($path) {
                return $storage->read($path);
            });

            if (!$file) {
                throw new FileNotFoundException();
            }

            return $file;
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), "fileSystem-read");
        }

        throw new RuntimeException('Не удалось прочитать файл!');
    }

    private function executeWithStorages(StorageCriteriaDTO $criteria, callable $operation)
    {
        try {
            $storages = $this->finder->find($criteria);
            foreach ($storages as $storage) {
                try {
                    return $operation($storage);
                } catch (\Throwable $exception) {
                    Yii::error($exception->getMessage(), "fileSystem-storage");
                }
            }
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), "fileSystem-main");
        }

        return null;
    }

    public function checkAvailability(File $file): array
    {
        $storages['mainStorage'] = $this->storage->checkAvailability($file);
        return $storages;
    }
}