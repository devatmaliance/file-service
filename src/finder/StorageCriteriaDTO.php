<?php

namespace devatmaliance\file_service\finder;

class StorageCriteriaDTO
{
    public ?string $baseUrl;
    public ?string $type;
    public ?int $priority;
    public ?int $permission;
    public bool $defaultStorage = true;
    public ?string $category;
}
