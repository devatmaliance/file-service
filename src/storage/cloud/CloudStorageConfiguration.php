<?php

namespace devatmaliance\file_service\storage\cloud;

use devatmaliance\file_service\storage\BaseStorageConfiguration;

class CloudStorageConfiguration extends BaseStorageConfiguration
{
    private string $bucket;
    private string $version;
    private string $region;
    private string $key;
    private string $secret;
    private string $endpoint;

    public function __construct(
        string $endpoint,
        string $baseUrl,
        string $type,
        int    $priority,
        int    $permissions,
        string $bucket,
        string $version,
        string $region,
        string $key,
        string $secret,
        bool   $isDefaultStorage = true,
        array  $categories = []
    )
    {
        $this->endpoint = $endpoint;
        $this->bucket = $bucket;
        $this->version = $version;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        parent::__construct($baseUrl, $type, $priority, $permissions, $isDefaultStorage, $categories);
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function getConnection(): array
    {
        return [
            'version' => $this->version,
            'region' => $this->region,
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret
            ],
            'endpoint' => $this->endpoint,
            'bucket' => $this->bucket,
        ];
    }

    public static function fromArray(array $config): self
    {
        $reflector = new \ReflectionClass(self::class);
        $constructor = $reflector->getConstructor();
        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            if (isset($config[$name])) {
                $args[] = $config[$name];
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            } else {
                throw new \Exception("Missing parameter: $name");
            }
        }

        return $reflector->newInstanceArgs($args);
    }
    // TODO:: fromFile
}