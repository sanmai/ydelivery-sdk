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

use CommonSDK\Contracts;
use CommonSDK\Types as CommonSDK;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use YDeliverySDK\Responses\Bad\BadRequestResponse;
use YDeliverySDK\Responses\Bad\ConflictResponse;
use YDeliverySDK\Responses\Bad\NotFoundResponse;
use YDeliverySDK\Responses\Bad\UnauthorizedResponse;

/**
 * Class Client.
 */
final class Client extends CommonSDK\Client
{
    use Requests\Shortcuts;

    protected const ERROR_CODE_RESPONSE_CLASS_MAP = [
        HttpResponse::HTTP_UNAUTHORIZED   => UnauthorizedResponse::class,
        HttpResponse::HTTP_NOT_FOUND      => NotFoundResponse::class,
        HttpResponse::HTTP_BAD_REQUEST    => BadRequestResponse::class,
        HttpResponse::HTTP_CONFLICT       => ConflictResponse::class,
    ];

    protected function isTextResponse(string $header): bool
    {
        return parent::isTextResponse($header) || 0 === \strpos($header, self::TEXT_CONTENT_TYPE);
    }

    protected function postDeserialize(ResponseInterface $httpResponse, Contracts\Response $response): void
    {
    }

    private const TEXT_CONTENT_TYPE = 'text/plain';
}
