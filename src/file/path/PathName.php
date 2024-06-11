<?php

namespace devatmaliance\file_service\file\path;

use devatmaliance\file_service\utility\FileUtility;

class PathName
{
    private string $name;
    private string $prefix;

    public function __construct(string $name, string $prefix = '')
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    /**
     * @param int $length
     * @param string $prefix
     * @return self
     */
    public static function generate(int $length = 20, string $prefix = ''): self
    {
        return new self(FileUtility::generateRandomString($length), $prefix);
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getName($path));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->prefix . $this->name;
    }
}
