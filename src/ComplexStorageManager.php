<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\register\FileRegister;
use devatmaliance\file_service\repository\StorageInfo;
use devatmaliance\file_service\repository\StorageRepository;
use devatmaliance\file_service\storage\Storage;
use RuntimeException;
use Yii;

class ComplexStorageManager implements StorageManager
{
    private StorageRepository $repository;
    private FileRegister $register;

    public function __construct(StorageRepository $repository, FileRegister $register)
    {
        $this->repository = $repository;
        $this->register = $register;
    }

    public function write(File $file, Path $aliasPath, ?StorageInfo $storageInfo = null): Path
    {
        $path = $this->executeWithStorages($storageInfo, function (Storage $storage) use ($file) {
            return $storage->write($file);
        });

        if (!$path) {
            throw new RuntimeException('Не удалось сохранить файл!');
        }

        return $this->register->register($path, $aliasPath);
    }

    public function read(Path $path, ?StorageInfo $storageInfo = null): File
    {
        if ($path->getBaseUrl()->getHost()->get() === 'hello') {
            $path = $this->register->get($path);
        }

        $file = $this->executeWithStorages($storageInfo, function (Storage $storage) use ($path) {
            return $storage->read($path);
        });

        if (!$file) {
            throw new RuntimeException('Не удалось прочитать файл!');
        }

        return $file;
    }

    private function executeWithStorages(StorageInfo $storageInfo, callable $operation)
    {
        try {
            $storages = $this->repository->find($storageInfo);
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