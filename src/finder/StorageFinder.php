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
     * @param StorageCriteriaDTO $criteria
     * @return array
     * @throws StorageNotFoundException
     */
    public function find(StorageCriteriaDTO $criteria): array
    {
        $filteredStorages = array_filter($this->storages, function (Storage $storage) use ($criteria) {
            return $this->matchesStorage($storage, $criteria);
        });

        if (empty($filteredStorages)) {
            throw StorageNotFoundException::withStorageCriteria($criteria);
        }

        usort($filteredStorages, function (Storage $storage1, Storage $storage2) {
            return $storage1->getConfig()->getPriority() <=> $storage2->getConfig()->getPriority();
        });

        return $filteredStorages;
    }

    private function matchesStorage(Storage $storage, StorageCriteriaDTO $criteria): bool
    {
        if (!empty($criteria->getBaseUrl()) && $storage->getConfig()->getBaseUrl() !== $criteria->getBaseUrl()) {
            return false;
        }

        if (!empty($criteria->getType()) && $storage->getConfig()->getType() !== $criteria->getType()) {
            return false;
        }

        if (!empty($criteria->getPriority()) && $storage->getConfig()->getPriority() !== $criteria->getPriority()) {
            return false;
        }

        if (!empty($criteria->getPermissions()) && $storage->getConfig()->getPermissions() !== $criteria->getPermissions()) {
            return false;
        }

        return true;
    }
}