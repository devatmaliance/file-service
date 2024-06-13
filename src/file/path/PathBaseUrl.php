<?php

namespace devatmaliance\file_service\file\path;


class PathBaseUrl
{
    private PathScheme $scheme;
    private PathHost $host;

    public function __construct(PathScheme $scheme, PathHost $host)
    {
        $this->scheme = $scheme;
        $this->host = $host;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        $scheme = PathScheme::fromPath($path);
        $host = PathHost::fromPath($path);

        return new self($scheme, $host);
    }

    public function getScheme(): PathScheme
    {
        return $this->scheme;
    }

    public function getHost(): PathHost
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $scheme = $this->scheme->get();
        $host = $this->host->get();

        if (!empty($scheme) && !empty($host)) {
            return $scheme . '://' . $host;
        }

        if (!empty($host)) {
            return $host;
        }

        return '';
    }
}