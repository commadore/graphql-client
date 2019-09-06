<?php

namespace GraphQLClient;

use GraphQLClient\Exceptions\GraphQLPayloadException;
use GraphQLClient\Exceptions\InvalidJsonException;
use GraphQLClient\Interfaces\GraphQLResponseInterface;
use Psr\Http\Message\ResponseInterface;

class GraphQLResponse implements GraphQLResponseInterface
{
    private $payload = [];
    private $errors = [];

    public function __construct(ResponseInterface $response)
    {
        $body = $response->getBody();

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

        $this->payload = $decodedResponse['data'] ?? [];
        $this->errors = $decodedResponse['errors'] ?? [];
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
        return $this->getErrors();
    }
}
