<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
use Symfony\Contracts\EventDispatcher\Event;

final class FailedFileRegistrationEvent extends Event implements FileRegistrationEventAdapter
{
    const NAME = 'file.registration_failed';

    private Path $filePath;
    private Path $aliasPath;
    private \Throwable $exception;

    public function __construct(Path $filePath, Path $aliasPath, \Throwable $exception)
    {
        $this->filePath = $filePath;
        $this->aliasPath = $aliasPath;
        $this->exception = $exception;
    }

    public function getFilePath(): Path
    {
        return $this->filePath;
    }

    public function getAliasPath(): Path
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