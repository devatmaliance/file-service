<?php

namespace devatmaliance\file_service\storage\s3;

use Aws\S3\S3Client;
use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FileContent;
use devatmaliance\file_service\file\FilePath;
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

    public function write(File $file): FilePath
    {
        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $file->getPath(),
            'Body' => $file->getContent(),
            'ACL' => 'public-read',
        ]);

        return FilePath::fromPath($result['ObjectURL']);
    }

    public function read(FilePath $path): File
    {
        $filePath = $path->get();
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $filePath,
        ]);

        $content = $result['Body']->getContents();
        if (!is_string($content)) {
            throw new FileReadException('Failed to read file: ' . $filePath);
        }

        return new File(new FileContent($content), $path);
    }

    public function checkAvailability(): bool
    {
        try {
            $this->client->listBuckets();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}