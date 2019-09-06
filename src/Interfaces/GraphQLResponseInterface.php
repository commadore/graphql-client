<?php

namespace GraphQLClient\Interfaces;

interface GraphQLResponseInterface
{
    public function getPayload(): array;

    public function hasErrors(): bool;

    public function getErrors(): array;
}
