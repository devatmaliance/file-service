<?php

namespace devatmaliance\file_service\storage\local;

use devatmaliance\file_service\storage\BaseStorageConfiguration;

class LocalStorageConfiguration extends BaseStorageConfiguration
{
    private string $storagePath;

    public function __construct(string $baseUrl, string $type, int $priority, int $permissions, string $storagePath, bool $isDefaultStorage, array $categories = [])
    {
        $this->storagePath = $storagePath;
        parent::__construct($baseUrl, $type, $priority, $permissions, $isDefaultStorage, $categories);
    }

    public function getStoragePath(): string
    {
        return $this->storagePath;
    }
}