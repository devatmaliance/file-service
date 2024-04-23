<?php

namespace devatmaliance\file_service\storage\s3;

use Aws\S3\S3Client;
use devatmaliance\file_service\storage\Storage;

class S3Storage implements Storage
{
    private S3Client $client;
    private string $bucket;

    public function __construct(array $config, string $bucket)
    {
        $this->client = new S3Client($config);
        $this->bucket = $bucket;
    }

    public function write(string $path, string $content): string
    {
        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
            'Body' => $content,
            'ACL' => 'public-read',
        ]);

        return $result['ObjectURL'];
    }
}