<?php

namespace devatmaliance\file_service\storage\cloud;

use Aws\S3\S3Client;
use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\Content;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\MimeType;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\storage\BaseStorageConfiguration;
use devatmaliance\file_service\storage\Storage;

class CloudStorage implements Storage
{
    private string $bucket;
    private S3Client $client;
    private CloudStorageConfiguration $config;

    public function __construct(CloudStorageConfiguration $config)
    {
        $this->client = new S3Client($config->getConnection());
        $this->bucket = $config->getBucket();
        $this->config = $config;
    }

    public function getConfig(): BaseStorageConfiguration
    {
        return $this->config;
    }

    public function write(File $file): Path
    {
        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $file->getPath()->get(),
            'Body' => $file->getContent()->get(),
            'ACL' => 'public-read',
            'ContentType' => $file->getMimeType()->get()
        ]);

        return Path::fromPath($result['ObjectURL']);
    }

    public function read(Path $path): File
    {
        $relativePath = $path->getRelativePath()->get();
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $relativePath,
        ]);

        try {
            $content = $result['Body']->getContents();
            if (!is_string($content)) {
                throw new FileReadException('Failed to read file: ' . $relativePath);
            }
        } catch (\Throwable $e) {
            $content = file_get_contents($path->get());
            if (!is_string($content)) {
                throw new FileReadException('Failed to read file: ' . $path->get());
            }
        }

        return new File(new Content($content), $path, MimeType::fromPath($path->get()));
    }

    public function remove(Path $path): void
    {
        $this->client->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $path->get(),
        ]);
    }

    public function checkAvailability(File $file): bool
    {
        try {
            $this->write($file);
            $this->remove($file->getPath());

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}