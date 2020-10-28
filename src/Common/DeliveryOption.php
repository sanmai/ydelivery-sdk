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

namespace YDeliverySDK\Common;

use JMS\Serializer\Annotation as JMS;

/*
 *
 * @property-read int $tariffId Идентификатор тарифа.
 * @property-read int $delivery Стоимость доставки.
 * @property-read int $deliveryForCustomer Стоимость доставки для покупателя.
 * @property-read int $partnerId Идентификатор службы доставки.
 * @property-read int $deliveryIntervalId Идентификатор интервала времени, в который нужно доставить заказ покупателю.
 * @property-read DateTimeInterface $calculatedDeliveryDateMin Начальная дата доставки в формате YYYY-MM-DD.
 * @property-read DateTimeInterface $calculatedDeliveryDateMax Конечная дата доставки в формате YYYY-MM-DD.
 * @property-read DeliveryService[] $services Данные о дополнительных услугах доставки.
 *
 *
 * @property-write int $tariffId Идентификатор тарифа.
 * @property-write int $delivery Стоимость доставки.
 * @property-write int $deliveryForCustomer Стоимость доставки для покупателя.
 * @property-write int $partnerId Идентификатор службы доставки.
 * @property-write int $deliveryIntervalId Идентификатор интервала времени, в который нужно доставить заказ покупателю.
 * @property-write DateTimeInterface $calculatedDeliveryDateMin Начальная дата доставки в формате YYYY-MM-DD.
 * @property-write DateTimeInterface $calculatedDeliveryDateMax Конечная дата доставки в формате YYYY-MM-DD.
 */

/**
 * Основа для объектов, используемых в запросах и ответах.
 */
abstract class DeliveryOption
{
    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $tariffId;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $delivery;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $deliveryForCustomer;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $type;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $partnerId;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $deliveryIntervalId;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    protected $calculatedDeliveryDateMin;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    protected $calculatedDeliveryDateMax;

    /**
     * @JMS\Type("array<YDeliverySDK\Common\DeliveryService>")
     * @JMS\SkipWhenEmpty
     *
     * @var DeliveryService[]
     */
    protected $services = [];

    /**
     * @param string|\DateTimeInterface $date
     */
    protected function setCalculatedDeliveryDateMin($date): void
    {
        $this->calculatedDeliveryDateMin = self::dateStringToDateTime($date);
    }

    /**
     * @param string|\DateTimeInterface $date
     */
    protected function setCalculatedDeliveryDateMax($date): void
    {
        $this->calculatedDeliveryDateMax = self::dateStringToDateTime($date);
    }

    /**
     * @param string|\DateTimeInterface $date
     *
     * @return \DateTimeInterface
     */
    private static function dateStringToDateTime($date)
    {
        if (!$date instanceof \DateTimeInterface) {
            return new \DateTimeImmutable((string) $date);
        }

        return $date;
    }
}
