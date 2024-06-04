<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\FilePath;
use Symfony\Contracts\EventDispatcher\Event;

final class FailedFileRegistrationEvent extends Event implements FileRegistrationEventAdapter
{
    const NAME = 'file.registration_failed';

    private FilePath $filePath;
    private FilePath $aliasPath;
    private \Throwable $exception;

    public function __construct(FilePath $filePath, FilePath $aliasPath, \Throwable $exception)
    {
        $this->filePath = $filePath;
        $this->aliasPath = $aliasPath;
        $this->exception = $exception;
    }

    public function getFilePath(): FilePath
    {
        return $this->filePath;
    }

    public function getAliasPath(): FilePath
    {
        return $this->aliasPath;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }

    public function adaptForYii2(): Yii2FailedFileRegistrationEvent
    {
        return new Yii2FailedFileRegistrationEvent($this);
    }
}