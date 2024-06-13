<?php

namespace devatmaliance\file_service\storage\s3;

use Aws\S3\S3Client;
use devatmaliance\file_service\exception\FileReadException;
use devatmaliance\file_service\file\Content;
use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\MimeType;
use devatmaliance\file_service\file\path\Path;
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

        $content = $result['Body']->getContents();
        if (!is_string($content)) {
            throw new FileReadException('Failed to read file: ' . $relativePath);
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