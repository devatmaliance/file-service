<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\storage\Storage;
use RuntimeException;
use Yii;

class FileSystem
{
    private Storage $mainStorage;
    private Storage $backupStorage;

    public function __construct(Storage $mainStorage, Storage $backupStorage)
    {
        $this->mainStorage = $mainStorage;
        $this->backupStorage = $backupStorage;
    }

    public function write(File $file): FilePath
    {
        try {
            return $this->mainStorage->write($file);
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), 'fileService-main');
        }

        try {
            return $this->backupStorage->write($file);
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), 'fileService-backup');
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