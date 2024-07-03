<?php

namespace devatmaliance\file_service\storage;

abstract class BaseStorageConfiguration
{
    public const READ_ONLY = 4;
    public const READ_WRITE = 7;
    public const CLOUD_TYPE = 'cloud';
    public const LOCAL_TYPE = 'local';

    protected string $type;
    protected string $baseUrl;
    protected int $priority;
    protected int $permissions;

    public function __construct(string $baseUrl, string $type, int $priority, int $permissions)
    {
        $this->baseUrl = $baseUrl;
        $this->type = $type;
        $this->priority = $priority;
        $this->permissions = $permissions;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }
}