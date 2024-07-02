<?php

namespace devatmaliance\file_service\repository;

use devatmaliance\file_service\exception\StorageNotFoundException;

class StorageRepository
{
    private array $availableStorages;

    public function __construct(array $availableStorages)
    {
        $this->availableStorages = $availableStorages;
    }

    /**
     * Поиск хранилищ по заданным критериям.
     *
     * @param StorageInfo $criteria
     * @return array
     * @throws StorageNotFoundException
     */
    public function find(StorageInfo $criteria): array
    {
        $filteredStorages = array_filter($this->availableStorages, function (StorageInfo $storage) use ($criteria) {
            return $this->matchesStorageInfo($storage, $criteria);
        });

        if (empty($filteredStorages)) {
            throw StorageNotFoundException::withStorageInfo($criteria);
        }

        usort($filteredStorages, function (StorageInfo $info1, StorageInfo $info2) {
            return $info1->getPriority() <=> $info2->getPriority();
        });

        return array_map(function (StorageInfo $info) {
            return $info->getStorage();
        }, $filteredStorages);
    }

    private function matchesStorageInfo(StorageInfo $storage, StorageInfo $criteria): bool
    {
        if (!empty($criteria->getHost()) && $storage->getHost() !== $criteria->getHost()) {
            return false;
        }

        if (!empty($criteria->getType()) && $storage->getType() !== $criteria->getType()) {
            return false;
        }

        if (!empty($criteria->getPriority()) && $storage->getPriority() !== $criteria->getPriority()) {
            return false;
        }

        if (!empty($criteria->getPermission()) && $storage->getPermission() !== $criteria->getPermission()) {
            return false;
        }

        return true;
    }
}