<?php

namespace Commadore\GraphQLClient\Test;

use Commadore\GraphQLClient\Client;
use Commadore\GraphQLClient\Exceptions\NetworkException;
use Commadore\GraphQLClient\GraphQLResponse;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $guzzleClient;

    public function setUp(): void
    {
        $this->guzzleClient = $this->createMock(ClientInterface::class);
    }

    public function testClientQuery_throwsNetworkException_whenTransferException()
    {
        $this->guzzleClient->method('request')
            ->willThrowException(new TransferException('Timeout'));

        $sut = new Client($this->guzzleClient);
        $this->expectException(NetworkException::class);
        $sut->query('test');
    }

    public function testClientQuery_returnsGraphQLResponse_WhenGuzzleReturnsGoodResponse()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], json_encode(['data' => ['Foo' => 'Bar']]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        $sut = new Client($client);

        $graphQLResponse = $sut->query('test', ['variables']);
        $payload = $graphQLResponse->getPayload();
        $this->assertInstanceOf(GraphQLResponse::class, $graphQLResponse);
        $this->assertEquals('Bar',$payload['Foo']);
    }
}
