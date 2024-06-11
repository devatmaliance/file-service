<?php

namespace devatmaliance\file_service\file;

class Content
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function fromPath(string $path): self
    {
        return new self(file_get_contents($path));
    }

    public function get(): string
    {
        return $this->content;
    }
}