<?php

namespace devatmaliance\file_service\storage\local;

use devatmaliance\file_service\storage\Storage;
use Throwable;

class LocalStorage implements Storage
{
    public function write(string $path, string $content): string
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }

        if (file_put_contents($path, $content) === false) {
            throw new \Exception('file_put_contents ' . $path);
        }

        return $path;
    }
}