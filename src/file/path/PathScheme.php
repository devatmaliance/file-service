<?php

namespace devatmaliance\file_service\file\path;

use devatmaliance\file_service\utility\FileUtility;

class PathScheme
{
    private string $scheme;

    public function __construct(string $scheme = '')
    {
        if (!empty($scheme) && !in_array($scheme, ['http', 'https'])) {
            throw new \InvalidArgumentException('Invalid scheme ' . $scheme);
        }

        $this->scheme = $scheme;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        return new self(FileUtility::getScheme($path));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->scheme;
    }
}