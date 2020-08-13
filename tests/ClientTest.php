<?php

/**
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018-2020 Alexey Kopytko <alexey@kopytko.com> and contributors
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\YDeliverySDK;

use Gamez\Psr\Log\TestLogger;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LogLevel;
use Tests\YDeliverySDK\Fixtures\FixtureLoader;
use YDeliverySDK\Client;
use YDeliverySDK\ClientBuilder;
use YDeliverySDK\Contracts\ParamRequest;
use YDeliverySDK\Contracts\Request;
use YDeliverySDK\Contracts\Response;
use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\PostalCodeRequest;
use YDeliverySDK\Responses\DeliveryOptionsResponse;
use YDeliverySDK\Responses\FileResponse;
use YDeliverySDK\Responses\PostalCodeResponse;

/**
 * @covers \YDeliverySDK\Client
 */
class ClientTest extends TestCase
{
    private const DEFAULT_JSON = '[]';

    private $lastRequestOptions = [];

    /**
     * @return MockObject|ResponseInterface
     */
    private function getResponse(string $contentType = 'application/json', string $responseBody = self::DEFAULT_JSON, array $extraHeaders = [])
    {
        $extraHeaders['Content-Type'] = $contentType;

        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return \array_key_exists($headerName, $extraHeaders);
        }));
        $response->method('getHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return [$extraHeaders[$headerName]];
        }));

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($responseBody);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }

    /**
     * @return MockObject|ClientInterface
     */
    private function getHttpClient(string $contentType = 'application/json', string $responseBody = self::DEFAULT_JSON, array $extraHeaders = [])
    {
        $response = $this->getResponse($contentType, $responseBody, $extraHeaders);

        $http = $this->createMock(ClientInterface::class);
        $http->method('request')->will($this->returnCallback(function ($method, $address, array $options) use ($response) {
            $this->lastRequestOptions = $options;

            return $response;
        }));

        return $http;
    }

    private function newClient(ClientInterface $http = null): Client
    {
        $builder = new ClientBuilder();
        $builder->setGuzzleClient($http ?? $this->getHttpClient());

        return $builder->build();
    }

    public function test_client_can_handle_any_request()
    {
        $client = $this->newClient($this->getHttpClient('text/plain', 'example'));
        $response = $client->sendAnyRequest($this->createMock(Request::class));
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertEmpty($this->lastRequestOptions);
    }

    public function test_client_can_handle_attachments()
    {
        $client = $this->newClient($this->getHttpClient('application/pdf', '%PDF', [
            'Content-Disposition' => 'attachment; filename=testing123.pdf',
        ]));
        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertInstanceOf(FileResponse::class, $response);

        \assert($response instanceof FileResponse);

        $this->assertSame('%PDF', (string) $response->getBody());
        $this->assertEmpty($this->lastRequestOptions);
    }

    public function test_client_can_log_param_request()
    {
        $client = $this->newClient($this->getHttpClient());
        $client->setLogger($logger = new TestLogger());

        $request = new PostalCodeRequest();
        $response = $client->sendPostalCodeRequest($request);

        /** @var $response PostalCodeResponse */
        $this->assertInstanceOf(PostalCodeResponse::class, $response);

        $this->assertSame(3, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('content-type'));

        $this->assertTrue($logger->log->hasRecordsWithMessage(self::DEFAULT_JSON));
        $this->assertTrue($logger->log->hasRecordsWithPartialMessage('location/postal-code'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('method'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('location'));
    }

    public function test_client_can_log_json_response()
    {
        $client = $this->newClient($this->getHttpClient());
        $client->setLogger($logger = new TestLogger());

        $request = new DeliveryOptionsRequest();

        $response = $client->sendDeliveryOptionsRequest($request);

        /** @var $response DeliveryOptionsResponse */
        $this->assertInstanceOf(DeliveryOptionsResponse::class, $response);

        $this->assertSame(5, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('content-type'));

        $this->assertTrue($logger->log->hasRecordsWithMessage('{}'));
        $this->assertTrue($logger->log->hasRecordsWithMessage(self::DEFAULT_JSON));
        $this->assertTrue($logger->log->hasRecordsWithMessage('PUT /delivery-options'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('method'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('location'));
    }

    public function test_client_can_pass_through_common_exceptions()
    {
        $client = $this->newClient($http = $this->getHttpClient());

        $http->method('request')->will($this->returnCallback(function () {
            throw new \RuntimeException();
        }));

        $this->expectException(\RuntimeException::class);
        $client->sendRequest($this->createMock(Request::class));
    }

    public function test_client_can_pass_through_exceptions_without_response()
    {
        $client = $this->newClient($http = $this->getHttpClient());

        $http->method('request')->will($this->returnCallback(function () {
            throw new ServerException('', $this->createMock(RequestInterface::class));
        }));

        $this->expectException(ServerException::class);
        $client->sendRequest($this->createMock(Request::class));
    }

    public function test_client_can_handle_unknown_error_response()
    {
        $client = $this->newClient($http = $this->getHttpClient());
        $client->setLogger($logger = new TestLogger());

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(500);
        $responseMock->method('getReasonPhrase')->willReturn('Server error');

        $http->method('request')->will($this->returnCallback(function () use ($responseMock) {
            throw new ServerException('', $this->createMock(RequestInterface::class), $responseMock);
        }));

        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertSame(3, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertTrue($logger->log->hasRecordsWithPartialMessage('API responded with an HTTP error code'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('exception'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('error_code'));

        $this->assertInstanceOf(Response::class, $response);

        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $message) {
            $this->assertSame('500', $message->getErrorCode());
            $this->assertSame('Server error', $message->getMessage());
        }

        $this->assertInstanceOf(ResponseInterface::class, $response);

        \assert($response instanceof ResponseInterface);
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('Server error', $response->getReasonPhrase());
    }

    public function test_client_can_handle_error_response()
    {
        $client = $this->newClient($http = $this->getHttpClient());
        $client->setLogger($logger = new TestLogger());

        $responseMock = $this->getResponse('application/json', FixtureLoader::load('400_Bad_Request.json'));
        $responseMock->method('getStatusCode')->willReturn(400);
        $responseMock->method('getReasonPhrase')->willReturn('Bad Request');

        $http->method('request')->will($this->returnCallback(function () use ($responseMock) {
            throw new ServerException('', $this->createMock(RequestInterface::class), $responseMock);
        }));

        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertSame(3, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertTrue($logger->log->hasRecordsWithPartialMessage('API responded with an HTTP error code'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('exception'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('error_code'));

        $this->assertInstanceOf(Response::class, $response);

        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $message) {
            $this->assertSame('UNKNOWN', $message->getErrorCode());
            $this->assertSame("Required Long parameter 'partnerId' is not present", $message->getMessage());
        }
    }

    public function test_fails_on_unknown_method()
    {
        $this->expectException(\BadMethodCallException::class);

        $invalid = 'invalid';
        ($this->newClient())->{$invalid}();
    }

    public function test_param_post_request()
    {
        $request = new class() implements ParamRequest {
            public function getMethod(): string
            {
                return 'POST';
            }

            public function getAddress(): string
            {
                return '';
            }

            public function getResponseClassName(): string
            {
                return '';
            }

            public function getSerializationFormat(): string
            {
                return '';
            }

            public function getParams(): array
            {
                return [
                    'foo' => 'bar',
                ];
            }
        };

        $client = $this->newClient($this->getHttpClient('text/plain', 'example'));
        $client->sendRequest($request);

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertSame('bar', $this->lastRequestOptions['form_params']['foo']);
    }
}
