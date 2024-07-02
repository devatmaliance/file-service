<?php

namespace devatmaliance\file_service\exception;

use devatmaliance\file_service\repository\StorageInfo;
use Throwable;

class StorageNotFoundException extends \Exception
{
    public function __construct($message = "Not found storage!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withStorageInfo(StorageInfo $info): self
    {
        $message = "Storage not found with the following criteria: " . self::buildMessage($info);
        return new self($message);
    }

    private static function buildMessage(StorageInfo $info): string
    {
        $criteria = [];

        if ($info->getHost()) {
            $criteria[] = "Host: " . $info->getHost();
        }

        if ($info->getType()) {
            $criteria[] = "Type: " . $info->getType();
        }

        if ($info->getPriority()) {
            $criteria[] = "Priority: " . $info->getPriority();
        }

        if ($info->getPermission()) {
            $criteria[] = "Permissions: " . $info->getPermission();
        }

        return implode(", ", $criteria);
    }
}
