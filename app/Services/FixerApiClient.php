<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;

class FixerApiClient
{
    /**
     * Base URL for the Fixer.io API
     *
     * @var string
     */
    protected $baseUrl = 'https://data.fixer.io/api';

    /**
     * An instance of GuzzleHttp\Client
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Fixer API key
     *
     * @var string
     */
    private $apiKey = null;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
        $this->apiKey = env('FIXER_API_KEY', null);
    }

    /**
     * Sends a GET request to a specified Fixer API endpoint.
     * Decodes the JSON responses and returns an array.
     *
     * @param string $path API endpoint (e.g. `/latest`)
     * @param array $parameters Query parameters to send with the request.
     * @param array $headers HTTP headers to send with the request.
     *
     * @return array
     */
    public function get($path = '', $parameters = [], $headers = [])
    {
        $clientParams = [
            'headers' => $headers,
            'query' => $parameters,
            // Forward HTTP responses on API errors.
            'http_errors' => false,
        ];

        $query['access_key'] = $this->apiKey;

        $response = $this->client->request('GET', $this->baseUrl . $path, $clientParams);
        return json_decode($response->getBody(), true);
    }
}
