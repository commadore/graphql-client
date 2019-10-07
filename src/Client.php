<?php

namespace Commadore\GraphQLClient;

use Commadore\GraphQLClient\Exceptions\NetworkException;
use Commadore\GraphQLClient\Interfaces\ClientInterface as GraphQLClientInterfaceAlias;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;

class Client implements GraphQLClientInterfaceAlias
{
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    public function query(string $query, array $variables = [], array $headers = []): GraphQLResponse
    {
        $options = [
            'headers' => $headers,
            'json' => [
                'query' => $query,
            ],
        ];

        if (!empty($variables))
        {
            $options['json']['variables'] = (object) $variables;
        }

        try
        {
            $response = $this->httpClient->request('POST', '', $options);
        }
        catch (TransferException $e)
        {
            throw new NetworkException('Network Exception:' . $e->getMessage(), 0, $e);
        }

        return new GraphQLResponse($response);
    }
}
