<?php

namespace devatmaliance\file_service\register;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
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

    public function register(Path $filePath, RelativePath $aliasPath): Path
    {
        try {
            $aliasPath = $this->client->register($filePath, $aliasPath);
        } catch (\Throwable $exception) {
            $event = new FailedFileRegistrationEvent($filePath, $aliasPath, $exception);
            $this->dispatcher->dispatch($event);
        }

        $baseUrl = rtrim($this->client->getBaseUrl(), '/');
        $alias = ltrim($aliasPath->getRelativePath()->get(), '/');

        return Path::fromPath(FileUtility::concatenatePaths($baseUrl, $alias));
    }

    public function get(Path $path): Path
    {
        return $this->client->getPathByAlias($path->getRelativePath());
    }

    public function getBaseUrl(): Path
    {
        return Path::fromPath($this->client->getBaseUrl());
    }

    public function aliasExists(RelativePath $aliasPath): bool
    {
        return $this->client->aliasExists($aliasPath);
    }
}