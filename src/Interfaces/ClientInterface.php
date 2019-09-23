<?php

namespace Commadore\GraphQLClient\Interfaces;

use Commadore\GraphQLClient\GraphQLResponse;

interface ClientInterface
{
    function query(string $query, array $variables = [], array $headers = []): GraphQLResponse;
}
