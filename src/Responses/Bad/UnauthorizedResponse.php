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

namespace YDeliverySDK\Responses\Bad;

use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Contracts\HasErrorCode;
use YDeliverySDK\Contracts\Response;
use YDeliverySDK\Responses\Types\Message;

/**
 * Class UnauthorizedResponse.
 *
 * HTTP/1.1 401 Unauthorized
 * {"timestamp":"2020-01-01T00:00:00.000+0000","status":401,"error":"Unauthorized","message":"Unauthorized","path":"/api/example"}
 */
final class UnauthorizedResponse implements Response, HasErrorCode, \Countable
{
    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s.uO'>")
     *
     * @var \DateTimeImmutable
     */
    private $timestamp;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $status;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $error;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $message;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $path;

    public function hasErrors(): bool
    {
        return true;
    }

    public function getMessages()
    {
        return Message::from([$this]);
    }

    public function getErrorCode(): string
    {
        return (string) $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function count()
    {
        return 0;
    }
}
