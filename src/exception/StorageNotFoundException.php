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

    private static function buildMessage(StorageCriteriaDTO $criteriaDTO): string
    {
        $criteria = [];

        if ($criteriaDTO->baseUrl) {
            $criteria[] = "BaseUrl: " . $criteriaDTO->baseUrl;
        }

        if ($criteriaDTO->type) {
            $criteria[] = "Type: " . $criteriaDTO->type;
        }

        if ($criteriaDTO->priority) {
            $criteria[] = "Priority: " . $criteriaDTO->priority;
        }

        if ($criteriaDTO->permission) {
            $criteria[] = "Permissions: " . $criteriaDTO->permission;
        }

        return implode(", ", $criteria);
    }
}
