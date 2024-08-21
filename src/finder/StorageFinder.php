<?php

namespace devatmaliance\file_service\finder;

use devatmaliance\file_service\exception\StorageNotFoundException;
use devatmaliance\file_service\storage\Storage;

class StorageFinder
{
    private array $storages;

    public function __construct(array $storages)
    {
        $this->storages = $storages;
    }

    /**
     * Поиск хранилищ по заданным критериям.
     *
     * @param StorageCriteriaDTO $criteriaDTO
     * @return array
     * @throws StorageNotFoundException
     */
    public function find(StorageCriteriaDTO $criteriaDTO): array
    {
        $filteredStorages = array_filter($this->storages, function (Storage $storage) use ($criteriaDTO) {
            return $this->matchesStorage($storage, $criteriaDTO);
        });

        if (empty($filteredStorages)) {
            throw StorageNotFoundException::withStorageCriteria($criteriaDTO);
        }

        usort($filteredStorages, function (Storage $storage1, Storage $storage2) {
            return $storage1->getConfig()->getPriority() <=> $storage2->getConfig()->getPriority();
        });

        return $filteredStorages;
    }

    private function matchesStorage(Storage $storage, StorageCriteriaDTO $criteriaDTO): bool
    {
        $config = $storage->getConfig();

        if ($config->isDefaultStorage() === $criteriaDTO->defaultStorage) {
            return true;
        }

        if (!empty($criteriaDTO->category) && !in_array($criteriaDTO->category, $config->getCategories())) {
            return false;
        }

        if (!empty($criteriaDTO->baseUrl) && $config->getBaseUrl() !== $criteriaDTO->baseUrl) {
            return false;
        }

        if (!empty($criteriaDTO->type) && $config->getType() !== $criteriaDTO->type) {
            return false;
        }

        if (!empty($criteriaDTO->priority) && $config->getPriority() !== $criteriaDTO->priority) {
            return false;
        }

        if (!empty($criteriaDTO->permission) && $config->getPermissions() !== $criteriaDTO->permission) {
            return false;
        }

        return true;
    }
}