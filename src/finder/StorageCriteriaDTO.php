<?php

namespace devatmaliance\file_service\finder;

class StorageCriteriaDTO
{
    private ?string $baseUrl;
    private ?string $type;
    private ?int $priority;
    private ?string $permission;

    public function __construct(?string $baseUrl = null, ?string $type = null, ?int $priority = null, ?string $permission = null)
    {
        $this->baseUrl = $baseUrl;
        $this->type = $type;
        $this->priority = $priority;
        $this->permission = $permission;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getPermissions(): ?string
    {
        return $this->permission;
    }
}
