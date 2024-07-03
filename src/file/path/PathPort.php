<?php

namespace devatmaliance\file_service\file\path;

use devatmaliance\file_service\utility\FileUtility;

class PathPort
{
    private ?int $port;

    public function __construct(?int $port = null)
    {
        $this->port = $port;
    }

    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getPort($path));
    }

    public function get(): string
    {
        return $this->port;
    }
}