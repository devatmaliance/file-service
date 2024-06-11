<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\register\FileRegister;
use devatmaliance\file_service\storage\Storage;
use RuntimeException;
use Yii;

class FailoverStorageManager implements StorageManager
{
    private Storage $mainStorage;
    private Storage $backupStorage;
    private FileRegister $fileRegister;

    public function __construct(Storage $mainStorage, Storage $backupStorage, FileRegister $fileRegister)
    {
        $this->mainStorage = $mainStorage;
        $this->backupStorage = $backupStorage;
        $this->fileRegister = $fileRegister;
    }

    public function write(File $file, FilePath $aliasPath): FilePath
    {
        $storages = [
            'main' => $this->mainStorage,
            'backup' => $this->backupStorage
        ];

        /** @var Storage $storage */
        foreach ($storages as $storageName => $storage) {
            try {
                $filePath = $storage->write($file);
                return $this->fileRegister->register($filePath, $aliasPath);
            } catch (\Throwable $exception) {
                Yii::error($exception->getMessage(), "fileSystem-{$storageName}");
                continue;
            }
        }

        throw new RuntimeException('Не удалось сохранить файл!');
    }

    public function checkAvailability(File $file): array
    {
        $storages['mainStorage'] = $this->mainStorage->checkAvailability($file);
        $storages['backupStorage'] = $this->backupStorage->checkAvailability($file);

        return $storages;
    }
}