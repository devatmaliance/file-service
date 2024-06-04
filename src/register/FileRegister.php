<?php

namespace devatmaliance\file_service\register;

use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use devatmaliance\file_service\register\event\FailedFileRegistrationEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class FileRegister
{
    private FileRegisterClient $client;
    private EventDispatcherInterface $dispatcher;

    public function __construct(FileRegisterClient $client, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    public function register(FilePath $filePath, FilePath $aliasPath): FilePath
    {
        try {
            $this->client->register($filePath, $aliasPath);
        } catch (\Throwable $exception) {
            $event = new FailedFileRegistrationEvent($filePath, $aliasPath, $e);
            $this->dispatcher->dispatch($event);
        }


        return FilePath::fromPath($path);
    }
}