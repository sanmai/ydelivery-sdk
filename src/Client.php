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

namespace YDeliverySDK;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\SerializerInterface;
use JSONSerializer\Serializer;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use YDeliverySDK\Contracts\JsonRequest;
use YDeliverySDK\Contracts\ParamRequest;
use YDeliverySDK\Contracts\Request;
use YDeliverySDK\Contracts\Response;
use YDeliverySDK\Responses\Bad\BadRequestResponse;
use YDeliverySDK\Responses\Bad\NotFoundResponse;
use YDeliverySDK\Responses\Bad\UnauthorizedResponse;
use YDeliverySDK\Responses\ErrorResponse;
use YDeliverySDK\Responses\FileResponse;

/**
 * Class Client.
 *
 * @method Responses\DeliveryServicesResponse|Responses\Types\DeliveryService[] sendDeliveryServicesRequest(Requests\DeliveryServicesRequest $request)
 * @method Responses\PostalCodeResponse|Responses\Types\PostalCode[]            sendPostalCodeRequest(Requests\PostalCodeRequest $request)
 * @method Responses\LocationResponse|Responses\Types\Location[]                sendLocationRequest(Requests\LocationRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendWithdrawIntervalsRequest(Requests\WithdrawIntervalsRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendImportIntervalsRequest(Requests\ImportIntervalsRequest $request)
 * @method Responses\DeliveryOptionsResponse|Responses\Types\DeliveryOption[]   sendDeliveryOptionsRequest(Requests\DeliveryOptionsRequest $request)
 */
final class Client implements Contracts\Client
{
    use LoggerAwareTrait;

    /** @var ClientInterface */
    private $http;

    /** @var SerializerInterface|Serializer */
    private $serializer;

    public function __construct(ClientInterface $http, SerializerInterface $serializer)
    {
        $this->http = $http;
        $this->serializer = $serializer;
    }

    /**
     * @see \YDeliverySDK\Contracts\Client::sendRequest()
     *
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        try {
            $response = $this->http->request(
                $request->getMethod(),
                $request->getAddress(),
                $this->extractOptions($request)
            );
        } catch (BadResponseException $exception) {
            if ($this->logger) {
                $this->logger->debug('API responded with an HTTP error code {error_code}', [
                    'exception'  => $exception,
                    'error_code' => $exception->getCode(),
                ]);
            }

            if (!$exception->hasResponse()) {
                throw $exception;
            }

            $response = $exception->getResponse();

            if ($badResponse = $this->deserializeBadResponse($response)) {
                return $badResponse;
            }
        }

        return $this->deserialize($request, $response);
    }

    /** @phan-suppress PhanDeprecatedFunction */
    public function __call(string $name, array $arguments)
    {
        if (0 === \strpos($name, 'send')) {
            /** @psalm-suppress MixedArgument */
            return $this->sendRequest(...$arguments);
        }

        throw new \BadMethodCallException(\sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    /**
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress ArgumentTypeCoercion
     * @psalm-suppress MoreSpecificReturnType
     */
    private function deserialize(Request $request, ResponseInterface $response): Response
    {
        $contentType = $this->getContentTypeHeader($response);

        if ($this->logger) {
            $this->logger->debug('Content-Type: {content-type}', [
                'content-type' => $contentType,
            ]);
        }

        if (!$this->isTextResponse($contentType)) {
            if ($this->hasAttachment($response)) {
                return new FileResponse($response->getBody());
            }

            return ErrorResponse::withHTTPResponse($response);
        }

        $responseBody = (string) $response->getBody();

        if ($this->logger) {
            $this->logger->debug($responseBody);
        }

        return $this->serializer->deserialize($responseBody, $request->getResponseClassName(), $request->getSerializationFormat());
    }

    private const ERROR_CODE_RESPONSE_CLASS_MAP = [
        HttpResponse::HTTP_UNAUTHORIZED   => UnauthorizedResponse::class,
        HttpResponse::HTTP_NOT_FOUND      => NotFoundResponse::class,
        HttpResponse::HTTP_BAD_REQUEST    => BadRequestResponse::class,
    ];

    private function deserializeBadResponse(ResponseInterface $response): ?Response
    {
        if (!\array_key_exists($response->getStatusCode(), self::ERROR_CODE_RESPONSE_CLASS_MAP)) {
            return null;
        }

        $responseBody = (string) $response->getBody();

        if ($this->logger) {
            $this->logger->debug($responseBody);
        }

        return $this->serializer->deserialize(
            $responseBody,
            self::ERROR_CODE_RESPONSE_CLASS_MAP[$response->getStatusCode()],
            Request::SERIALIZATION_JSON
        );
    }

    private function serialize(Request $request): string
    {
        $requestBody = $this->serializer->serialize($request, Request::SERIALIZATION_JSON);

        if ($this->logger) {
            $this->logger->debug($requestBody);
        }

        return $requestBody;
    }

    private function hasAttachment(ResponseInterface $response): bool
    {
        if (!$response->hasHeader(self::CONTENT_DISPOSITION)) {
            return false;
        }

        return \strpos($response->getHeader(self::CONTENT_DISPOSITION)[0], 'attachment') === 0;
    }

    private function getContentTypeHeader(ResponseInterface $response): string
    {
        if ($response->hasHeader(self::CONTENT_TYPE)) {
            return $response->getHeader(self::CONTENT_TYPE)[0];
        }

        return '';
    }

    private function isTextResponse(string $header): bool
    {
        return 0 === \strpos($header, 'application/json');
    }

    private function extractOptions(Request $request): array
    {
        if ($this->logger) {
            $this->logger->debug('{method} {location}', [
                'method'   => $request->getMethod(),
                'location' => $request->getAddress(),
            ]);
        }

        if ($request instanceof ParamRequest) {
            if ($request->getMethod() === 'GET') {
                return [
                    RequestOptions::QUERY => $request->getParams(),
                ];
            }

            return [
                RequestOptions::FORM_PARAMS => $request->getParams(),
            ];
        }

        if ($request instanceof JsonRequest) {
            $requestBody = $this->serialize($request);

            if ($this->logger) {
                $this->logger->debug($requestBody);
            }

            return [
                RequestOptions::BODY    => $requestBody,
                RequestOptions::HEADERS => [
                    self::CONTENT_TYPE => 'application/json',
                ],
            ];
        }

        return [];
    }

    private const CONTENT_TYPE = 'Content-Type';
    private const CONTENT_DISPOSITION = 'Content-Disposition';
}
