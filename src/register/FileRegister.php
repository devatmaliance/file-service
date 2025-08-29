<?php

namespace devatmaliance\file_service\register;

use common\models\TelegramChannel;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\PathBaseUrl;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\register\client\FileRegisterClient;
use Psr\Log\LoggerInterface;
use Throwable;

class FileRegister
{
    private FileRegisterClient $client;

    public function __construct(FileRegisterClient $client)
    {
        $this->client = $client;
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

    public function isRegisteredFile(Path $path): bool
    {
        return $path->getBaseUrl()->getHost()->get() === $this->getBaseUrl()->getHost()->get();
    }
}
