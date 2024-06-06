<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\register\FileRegister;
use devatmaliance\file_service\storage\Storage;
use RuntimeException;
use Yii;

class DefaultStorageManager implements StorageManager
{
    private Storage $storage;
    private FileRegister $fileRegister;

    public function __construct(Storage $storage, FileRegister $fileRegister)
    {
        $this->storage = $storage;
        $this->fileRegister = $fileRegister;
    }

    public function write(File $file, FilePath $aliasPath): FilePath
    {
        try {
            $filePath = $this->storage->write($file);
            return $this->fileRegister->register($filePath, $aliasPath);
        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), "fileSystem-storage");
        }

        throw new RuntimeException('Не удалось сохранить файл!');
    }
}