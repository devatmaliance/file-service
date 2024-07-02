<?php

namespace devatmaliance\file_service\exception;

use devatmaliance\file_service\finder\StorageCriteriaDTO;
use Throwable;

class StorageNotFoundException extends \Exception
{
    public function __construct($message = "Not found storage!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withStorageCriteria(StorageCriteriaDTO $criteria): self
    {
        $message = "Storage not found with the following criteria: " . self::buildMessage($criteria);
        return new self($message);
    }

    private static function buildMessage(StorageCriteriaDTO $info): string
    {
        $criteria = [];

        if ($info->getBaseUrl()) {
            $criteria[] = "BaseUrl: " . $info->getBaseUrl();
        }

        if ($info->getType()) {
            $criteria[] = "Type: " . $info->getType();
        }

        if ($info->getPriority()) {
            $criteria[] = "Priority: " . $info->getPriority();
        }

        if ($info->getPermissions()) {
            $criteria[] = "Permissions: " . $info->getPermissions();
        }

        return implode(", ", $criteria);
    }
}
