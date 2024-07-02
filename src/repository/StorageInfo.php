<?php

namespace devatmaliance\file_service\repository;

use devatmaliance\file_service\storage\Storage;

class StorageInfo
{
    public const READ_ONLY = 4;
    public const READ_WRITE = 7;

    private string $type;
    private string $host;
    private int $priority;
    private int $permission;
    private Storage $storage;

    public function __construct(string $host, string $type, int $priority, int $permission, Storage $storage)
    {
        $this->host = $host;
        $this->type = $type;
        $this->priority = $priority;
        $this->permission = $permission;
        $this->storage = $storage;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getPermission(): int
    {
        return $this->permission;
    }
}