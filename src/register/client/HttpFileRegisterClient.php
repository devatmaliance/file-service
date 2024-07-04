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

    public function __construct(string $baseUrl, string $apiKey, $apiVersion, int $timeout = 30)
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
                'API-version' => $apiVersion
            ],
        ]);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function registerFile(Path $filePath, Path $aliasPath): void
    {
        $this->post('locations/register', [
            'url' => $filePath->get(),
            'alias' => $aliasPath->getRelativePath()->get(),
        ]);
    }

    public function reserveAlias(RelativePath $aliasPath): Path
    {
        $response = $this->post('locations/reserve', [
            'alias' => $aliasPath->get()
        ]);

        return Path::fromPath(FileUtility::concatenatePaths($response['host'], $response['alias']));
    }

    public function getPathByAlias(RelativePath $relativePath): Path
    {
        $response = $this->get('locations', [
            'alias' => $relativePath->get()
        ]);

        return Path::fromPath(FileUtility::concatenatePaths($response['host'], $response['relativePath']));
    }

    public function aliasExists(RelativePath $relativePath): bool
    {
        return $this->head('locations', [
            'alias' => $relativePath->get()
        ]);
    }

    /**
     * Отправляет HEAD запрос с указанными параметрами.
     *
     * @param string $uri
     * @param array $params
     * @return bool
     * @throws RequestException
     */
    public function head(string $uri, array $params = []): bool
    {
        try {
            $response = $this->client->head($uri, [
                'query' => $params,
            ]);
            return $response->getStatusCode() === 200;
        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                return false;
            }
            throw $e;
        }
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
        $response = $this->client->post($uri, [
            'json' => $body,
        ]);

        return $this->handleResponse($response);
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
        $response = $this->client->get($uri, [
            'query' => $params,
        ]);

        return $this->handleResponse($response);
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
        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);

        if (!$decodedBody && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return $decodedBody;
    }
}