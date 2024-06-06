<?php

namespace devatmaliance\file_service\register;

use devatmaliance\file_service\file\FilePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use devatmaliance\file_service\register\event\FailedFileRegistrationEvent;
use devatmaliance\file_service\utility\FileUtility;
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
            $aliasPath = $this->client->register($filePath, $aliasPath);
        } catch (\Throwable $exception) {
            $event = new FailedFileRegistrationEvent($filePath, $aliasPath, $exception);
            $this->dispatcher->dispatch($event);
        }

        $baseUrl = rtrim($this->client->getBaseUrl(), '/');
        $alias = ltrim($aliasPath->get(), '/');

        return FilePath::fromPath(FileUtility::concatenatePaths($baseUrl, $alias));
    }
}