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

use CommonSDK\Concerns\PropertyWrite;
use CommonSDK\Concerns\RequestCore;
use CommonSDK\Contracts\JsonRequest;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Responses\OrdersStatusResponse;

/**
 * @property-write int $senderId Идентификатор магазина.
 * @property-write int $fromOrderId Идентификатор заказа. Результаты будут выведены для заказов, идентификаторы которых строго больше указанного.
 *
 * @phan-file-suppress PhanAccessWriteOnlyMagicProperty
 */
final class OrdersStatusRequest implements JsonRequest
{
    use PropertyWrite;
    use RequestCore;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $senderId;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $fromOrderId;

    /**
     * @JMS\Type("array<YDeliverySDK\Requests\Types\OrderId>")
     *
     * @var Types\OrderId[]
     */
    private $orders = [];

    private const METHOD = 'PUT';
    private const ADDRESS = '/orders/status';

    private const RESPONSE = OrdersStatusResponse::class;

    public function __construct(int $senderId)
    {
        $this->senderId = $senderId;
    }

    public function addOrder(): Types\OrderId
    {
        $order = new Types\OrderId();

        $this->orders[] = $order;

        return $order;
    }
}
