<?php

namespace devatmaliance\file_service\register;

use common\models\TelegramChannel;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\PathBaseUrl;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use devatmaliance\file_service\register\event\FailedFileRegistrationEvent;
use devatmaliance\file_service\register\exception\BadRequestException;
use devatmaliance\file_service\register\exception\ConflictException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class FileRegister
{
    private FileRegisterClient $client;
    private EventDispatcherInterface $dispatcher;

    public function __construct(FileRegisterClient $client, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    public function registerFile(Path $filePath, RelativePath $aliasPath): Path
    {
        try {
            return $this->client->registerFile($filePath, $aliasPath);
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function get(Path $path): Path
    {
        return $this->client->getPathByAlias($path->getRelativePath());
    }

    public function getBaseUrl(): PathBaseUrl
    {
        return PathBaseUrl::fromPath($this->client->getBaseUrl());
    }

    public function aliasExists(RelativePath $aliasPath): bool
    {
        return $this->client->aliasExists($aliasPath);
    }

    public function isRegisteredFile(Path $path): bool
    {
        return $path->getBaseUrl()->getHost()->get() === $this->getBaseUrl()->getHost()->get();
    }

    public function compareHosts(Path $path1, Path $path2): bool
    {
        return $this->client->compareHosts($path1, $path2);
    }
}
