<?php

namespace devatmaliance\file_service\file\path;

class Path
{
    private PathBaseUrl $baseUrl;
    private RelativePath $relativePath;

    public function __construct(PathBaseUrl $baseUrl, RelativePath $relativePath)
    {
        $this->baseUrl = $baseUrl;
        $this->relativePath = $relativePath;
    }

    public static function fromPath(string $path): self
    {
        $baseUrl = PathBaseUrl::fromPath($path);
        $relativePath = RelativePath::fromPath($path);

        return new self($baseUrl, $relativePath);
    }

    public function getBaseUrl(): PathBaseUrl
    {
        return $this->baseUrl;
    }

    public function getRelativePath(): RelativePath
    {
        return $this->relativePath;
    }

    public function get(): string
    {
        $baseUrl = $this->baseUrl->get();
        $relativePath = $this->relativePath->get();

        if (!empty($baseUrl)) {
            return $baseUrl . '/' . ltrim($relativePath, '/');
        } else {
            return $relativePath;
        }
    }
}