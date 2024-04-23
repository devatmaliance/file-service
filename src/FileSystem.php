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
            return FilePath::fromPath($this->mainStorage->write($file->getPath(), $file->getContent()));
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), 'fileService-main');
        }

        try {
            return FilePath::fromPath($this->backupStorage->write($file->getPath(), $file->getContent()));
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), 'fileService-backup');
        }

        throw new RuntimeException('Не удалось сохранить файл!');
    }
}