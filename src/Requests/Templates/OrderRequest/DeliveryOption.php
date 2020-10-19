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

namespace YDeliverySDK\Requests\Templates\OrderRequest;

use CommonSDK\Concerns\PropertyWrite;
use CommonSDK\Contracts\ReadableRequestProperty;
use YDeliverySDK\Common;

/**
 * @property-write int $tariffId Идентификатор тарифа.
 * @property-write int $delivery Стоимость доставки.
 * @property-write int $deliveryForCustomer Стоимость доставки для покупателя.
 * @property-write int $partnerId Идентификатор службы доставки.
 * @property-write int $deliveryIntervalId Идентификатор интервала времени, в который нужно доставить заказ покупателю.
 * @property-write \DateTimeInterface $calculatedDeliveryDateMin Начальная дата доставки в формате YYYY-MM-DD.
 * @property-write \DateTimeInterface $calculatedDeliveryDateMax Конечная дата доставки в формате YYYY-MM-DD.
 */
final class DeliveryOption extends Common\DeliveryOption implements ReadableRequestProperty
{
    use PropertyWrite;

    public function __construct(array $services = [])
    {
        $this->services = $services;
    }

    public function addService(): DeliveryService
    {
        $service = new DeliveryService();

        $this->services[] = $service;

        return $service;
    }
}
