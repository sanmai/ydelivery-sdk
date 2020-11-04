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

namespace YDeliverySDK\Requests;

use CommonSDK\Concerns\ObjectPropertyRead;
use CommonSDK\Concerns\PropertyWrite;
use CommonSDK\Concerns\RequestCore;
use CommonSDK\Contracts\JsonRequest;
use CommonSDK\Types\ArrayProperty;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Responses\SubmitOrderResponse;

/**
 * SubmitOrderRequest.
 *
 * @see https://yandex.ru/dev/delivery-3/doc/dg/reference/post-orders-submit.html/
 *
 * @property-write int[] $orderIds
 */
final class SubmitOrderRequest implements JsonRequest
{
    use RequestCore;
    use ObjectPropertyRead;
    use PropertyWrite;

    /**
     * @JMS\Type("ArrayCollection<int>")
     *
     * @var ArrayProperty<int>
     */
    private $orderIds;

    private const METHOD = 'POST';
    private const ADDRESS = '/orders/submit';

    private const RESPONSE = SubmitOrderResponse::class;

    /**
     * @phan-suppress PhanTypeMismatchPropertyProbablyReal
     *
     * @param int[] $orderIds
     */
    public function __construct(array $orderIds = [])
    {
        $this->orderIds = new ArrayProperty($orderIds);
    }

    /**
     * @phan-suppress PhanTypeMismatchPropertyProbablyReal
     *
     * @param int[] $orderIds
     */
    protected function setOrderIds(array $orderIds): void
    {
        $this->orderIds = new ArrayProperty($orderIds);
    }

    /**
     * @phan-suppress PhanAccessWriteOnlyMagicProperty
     *
     * @deprecated
     */
    public function addOrder(int $orderId): self
    {
        $this->orderIds[] = $orderId;

        return $this;
    }
}
