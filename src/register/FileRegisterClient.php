<?php

namespace devatmaliance\file_service\register;

use devatmaliance\file_service\file\FilePath;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FileRegisterClient
{
    private Client $client;

    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function register(FilePath $storagePath, FilePath $publicPath): FilePath
    {
        $response = $this->post('register', [
            'storage_path' => $storagePath->get(),
            'public_path' => $publicPath->get(),
        ]);

        return $publicPath;
    }

    private function post(string $uri, array $body): ResponseInterface
    {
        $request = $this->createRequest('POST', $uri, $body);
        return $this->sendRequest($request);
    }

    private function createRequest(string $method, string $uri, ?array $body = null): RequestInterface
    {
        $options = [];
        if (!$body) {
            $options['body'] = json_encode($body);
        }

        return new Request($method, $uri, [], $options['body'] ?? null);
    }

    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (GuzzleException $e) {
            throw new Exception('HTTP request failed: ' . $e->getMessage());
        }
    }
}