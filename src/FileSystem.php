<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use devatmaliance\file_service\storage\Storage;
use RuntimeException;

class FileSystem
{
    private Storage $mainStorage;
    private Storage $backupStorage;

    public function __construct(Storage $mainStorage, Storage $backupStorage)
    {
        $this->mainStorage = $mainStorage;
        $this->backupStorage = $backupStorage;
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
                return $storage->write($file);
//                $filePath = $storage->write($file);
//                return $this->fileRegister->register($filePath, $aliasPath);
            } catch (\Throwable $exception) {
                Yii::error($exception->getMessage(), "fileSystem-{$storageName}");
                continue;
            }
        }

        throw new RuntimeException('Не удалось сохранить файл!');
    }

    public function checkAvailability(): array
    {
        $storages['mainStorage'] = $this->mainStorage->checkAvailability();
        $storages['backupStorage'] = $this->backupStorage->checkAvailability();

        return $storages;
    }
}