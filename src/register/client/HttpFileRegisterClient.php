<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\FilePath;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpFileRegisterClient implements FileRegisterClient
{
    private Client $client;
    private string $baseUrl;

    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function register(FilePath $filePath, FilePath $aliasPath): FilePath
    {
        $response = $this->post('locations', [
            'url' => $filePath->get(),
            'alias' => $aliasPath->get(),
        ]);

        return FilePath::fromPath($response['alias']);
    }

    private function post(string $uri, array $body): array
    {
        $request = $this->createRequest('POST', $uri, $body);
        return $this->sendRequest($request);
    }

    private function createRequest(string $method, string $uri, ?array $body = null): RequestInterface
    {
        $options = [];
        if ($body) {
            $options['body'] = json_encode($body);
        }

        return new Request($method, $uri, [], $options['body'] ?? null);
    }

    private function sendRequest(RequestInterface $request): array
    {
        try {
            return $this->handleResponse($this->client->send($request));
        } catch (\Throwable $e) {
            throw new Exception('HTTP request failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function handleResponse(ResponseInterface $response): array
    {
        $this->validateResponse($response);

        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);

        if (!$decodedBody && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return $decodedBody;
    }

    private function validateResponse(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new \Exception("Server returned error: $statusCode - $reasonPhrase");
        }

        if (!$response->getBody()->getSize()) {
            throw new \Exception("Empty response body");
        }
    }
}