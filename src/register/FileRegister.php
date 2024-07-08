<?php

namespace devatmaliance\file_service\register;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use devatmaliance\file_service\register\event\FailedFileRegistrationEvent;
use devatmaliance\file_service\register\exception\BadRequestException;
use devatmaliance\file_service\register\exception\ConflictException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class FileRegister
{
    private FileRegisterClient $client;
    private EventDispatcherInterface $dispatcher;

    public function __construct(FileRegisterClient $client, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    public function registerFile(Path $filePath, Path $aliasPath): void
    {
        try {
            $this->client->registerFile($filePath, $aliasPath);
        } catch (ConflictException|BadRequestException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            $event = new FailedFileRegistrationEvent($filePath, $aliasPath, $exception);
            $this->dispatcher->dispatch($event);
        }
    }

    public function reserveAlias(RelativePath $aliasPath): Path
    {
        return $this->client->reserveAlias($aliasPath);
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

    public function isRegisteredFile(Path $path): bool
    {
        return $path->getBaseUrl()->getHost()->get() === $this->getBaseUrl()->get();
    }
}