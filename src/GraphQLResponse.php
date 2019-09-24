<?php

namespace Commadore\GraphQLClient;

use Commadore\GraphQLClient\Exceptions\GraphQLPayloadException;
use Commadore\GraphQLClient\Exceptions\InvalidJsonException;
use Commadore\GraphQLClient\Interfaces\GraphQLResponseInterface;
use Psr\Http\Message\ResponseInterface;

class GraphQLResponse implements GraphQLResponseInterface
{
    private $payload = [];
    private $errors = [];

    public function __construct(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        $jsonDecoded = json_decode($body, true);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE)
        {
            throw new InvalidJsonException(
                'Invalid JSON response. Response body: ' . $body
            );
        }

        if (array_key_exists('data', $jsonDecoded) === false && empty($jsonDecoded['errors']))
        {
            throw new GraphQLPayloadException(
                'Invalid GraphQL JSON response. Response body: ' . json_encode($jsonDecoded)
            );
        }

        $this->payload = $jsonDecoded['data'] ?? [];
        $this->errors = $jsonDecoded['errors'] ?? [];
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
