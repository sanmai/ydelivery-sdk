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

namespace YDeliverySDK\Responses\Bad\BadRequestResponse;

use CommonSDK\Concerns\PropertyRead;
use CommonSDK\Contracts\HasErrorCode;
use CommonSDK\Types\Message;
use JMS\Serializer\Annotation as JMS;

/**
 * Validation error.
 *
 * Can be like:
 *
 * {
 *    "objectName": "senderOrderDraft",
 *    "field": "deliveryType",
 *    "message": "must not be null",
 *    "conditionCode": "NotNull",
 *    "arguments": {},
 *    "errorCode": "FIELD_NOT_VALID"
 * }
 *
 * Or like:
 *
 * {
 *    "errorCode": "OPTION_SERVICE_NOT_FOUND",
 *    "extraService": "RETURN_SORT"
 * }
 *
 * Or like:
 *
 * {
 *    "errorCode": "OPTION_CALCULATED_DATE_MIN_MISMATCH",
 *    "currentValue": null,
 *    "actualValue": "2020-10-31"
 * }
 *
 * @property-read string|null $objectName
 * @property-read string|null $field
 * @property-read string|null $message
 * @property-read string|null $conditionCode
 * @property-read string $errorCode
 * @property-read string|null $extraService
 * @property-read string|null $currentValue
 * @property-read string|null $actualValue
 */
final class ValidationError implements HasErrorCode
{
    use PropertyRead;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $objectName;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $field;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $message;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $conditionCode;

    /**
     * @JMS\Type("array<string, string>")
     *
     * @var array<string, string>
     *
     * @todo Needs an example.
     */
    private $arguments = [];

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $errorCode;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $extraService;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $currentValue;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $actualValue;

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getMessage(): string
    {
        if ($this->objectName !== null && $this->field !== null && $this->message !== null) {
            return \sprintf('%s->%s %s', $this->objectName, $this->field, $this->message);
        }

        foreach (\get_object_vars($this) as $key => $value) {
            if (!\is_string($value)) {
                continue;
            }

            if ($key === 'errorCode') {
                continue;
            }

            return $value;
        }

        return $this->errorCode;
    }
}
