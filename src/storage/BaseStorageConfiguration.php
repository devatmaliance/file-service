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
    protected array $categories;
    protected int $priority;
    protected int $permissions;
    protected bool $default;

    public function __construct(string $baseUrl, string $type, int $priority, int $permissions, bool $default, array $categories = [])
    {
        $this->baseUrl = $baseUrl;
        $this->type = $type;
        $this->priority = $priority;
        $this->permissions = $permissions;
        $this->default = $default;
        $this->categories = $categories;
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

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function isDefaultStorage(): bool
    {
        return $this->default;
    }
}