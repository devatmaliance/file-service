<?php

namespace devatmaliance\file_service\storage;

use Closure;
use InvalidArgumentException;

class InMemoryStorageWriteAvailabilityCache implements StorageWriteAvailabilityCache
{
    private int $ttlSeconds;
    private Closure $clock;
    private array $unavailableUntil = [];

    public function __construct(int $ttlSeconds = 30, ?callable $clock = null)
    {
        if ($ttlSeconds < 1) {
            throw new InvalidArgumentException('Storage unavailability TTL must be greater than zero');
        }

        $this->ttlSeconds = $ttlSeconds;
        $this->clock = $clock ? Closure::fromCallable($clock) : static function (): int {
            return time();
        };
    }

    public function isAvailable(Storage $storage): bool
    {
        $key = $this->getStorageKey($storage);

        if (!isset($this->unavailableUntil[$key])) {
            return true;
        }

        if ($this->unavailableUntil[$key] > $this->now()) {
            return false;
        }

        unset($this->unavailableUntil[$key]);

        return true;
    }

    public function markUnavailable(Storage $storage): void
    {
        $this->unavailableUntil[$this->getStorageKey($storage)] = $this->now() + $this->ttlSeconds;
    }

    public function markAvailable(Storage $storage): void
    {
        unset($this->unavailableUntil[$this->getStorageKey($storage)]);
    }

    private function getStorageKey(Storage $storage): string
    {
        $config = $storage->getConfig();

        return $config->getType() . '|' . $config->getBaseUrl();
    }

    private function now(): int
    {
        return ($this->clock)();
    }
}
