<?php

namespace devatmaliance\file_service\file\path;

use devatmaliance\file_service\utility\FileUtility;

class PathHost
{
    private string $host;

    public function __construct(string $host = '')
    {
        $host = ltrim($host, '/');
        $host = rtrim($host, '/');

        $this->host = $host;
    }

    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getHost($path));
    }

    public function get(): string
    {
        return $this->host;
    }
}