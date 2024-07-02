<?php

namespace devatmaliance\file_service\repository;

class StorageCriteria
{
    private ?string $host;
    private ?string $type;
    private ?int $priority;
    private ?string $permission;

    public function __construct(?string $host, ?string $type, ?int $priority, ?string $permission)
    {
        $this->host = $host;
        $this->type = $type;
        $this->priority = $priority;
        $this->permission = $permission;
    }

    public static function t(): self
    {
        return new self(null, null, null, null);
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }
}
