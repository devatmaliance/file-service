<?php

namespace devatmaliance\file_service\storage;

interface StorageWriteAvailabilityCache
{
    public function isAvailable(Storage $storage): bool;

    public function markUnavailable(Storage $storage): void;

    public function markAvailable(Storage $storage): void;
}
