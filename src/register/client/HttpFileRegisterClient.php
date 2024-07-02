<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;
use devatmaliance\file_service\utility\FileUtility;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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

    public function register(Path $filePath, Path $aliasPath): Path
    {
        $response = $this->post('locations', [
            'url' => $filePath->get(),
            'alias' => $aliasPath->get(),
        ]);

        return Path::fromPath($response['alias']);
    }

    public function getPathByAlias(RelativePath $relativePath): Path
    {
        $response = $this->get('locations', [
            'alias' => $relativePath->get()
        ]);

        return Path::fromPath(FileUtility::concatenatePaths($response['host'], $response['relativePath']));
    }

    /**
     * Отправляет POST запрос с указанным телом.
     *
     * @param string $uri
     * @param array $body
     * @return array
     * @throws RequestException
     */
    public function post(string $uri, array $body): array
    {
        try {
            $response = $this->client->post($uri, [
                'json' => $body,
            ]);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            throw new Exception("POST request failed: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Отправляет GET запрос с указанными параметрами.
     *
     * @param string $uri
     * @param array $params
     * @return array
     * @throws RequestException
     */
    public function get(string $uri, array $params = []): array
    {
        try {
            $response = $this->client->get($uri, [
                'query' => $params,
            ]);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            throw new Exception("GET request failed: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Обрабатывает ответ Guzzle и возвращает декодированный JSON.
     *
     * @param ResponseInterface $response
     * @return array
     * @throws Exception
     */
    private function handleResponse(ResponseInterface $response): array
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