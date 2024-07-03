<?php

namespace devatmaliance\file_service\file\path;


class PathBaseUrl
{
    private PathScheme $scheme;
    private PathHost $host;
    private PathPort $port;

    public function __construct(PathScheme $scheme, PathHost $host, PathPort $port)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param string $path
     * @return self
     */
    public static function fromPath(string $path): self
    {
        $scheme = PathScheme::fromPath($path);
        $host = PathHost::fromPath($path);
        $port = PathPort::fromPath($path);

        return new self($scheme, $host, $port);
    }

    public function getScheme(): PathScheme
    {
        return $this->scheme;
    }

    public function getHost(): PathHost
    {
        return $this->host;
    }

    public function getPort(): PathPort
    {
        return $this->port;
    }

    public function get(): string
    {
        $scheme = $this->scheme->get();
        $host = $this->host->get();
        $port = $this->port->get();

        $baseUrlParts = [];

        if (!empty($scheme) && !empty($host)) {
            $baseUrlParts[] = $scheme . '://' . $host;
        } elseif (!empty($host)) {
            $baseUrlParts[] = $host;
        }

        if (!empty($port) && !empty($host)) {
            $baseUrlParts[] = ':' . $port;
        }

        return implode('', $baseUrlParts);
    }
}