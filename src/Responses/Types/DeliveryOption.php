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

namespace YDeliverySDK\Responses\Types;

use CommonSDK\Concerns\PropertyRead;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Responses\Types\DeliveryOption\Cost;
use YDeliverySDK\Responses\Types\DeliveryOption\Delivery;
use YDeliverySDK\Responses\Types\DeliveryOption\Service;
use YDeliverySDK\Responses\Types\DeliveryOption\Shipment;

final class DeliveryOption
{
    use PropertyRead;

    /** самый быстрый */
    public const TAG_FASTEST = 'FASTEST';

    /** самый дешевый */
    public const TAG_CHEAPEST = 'CHEAPEST';

    /** оптимальный */
    public const TAG_OPTIMAL = 'OPTIMAL';

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $tariffId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $tariffName;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\DeliveryOption\Cost")
     *
     * @var Cost
     */
    private $cost;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\DeliveryOption\Delivery")
     *
     * @var Delivery
     */
    private $delivery;

    /**
     * @JMS\Type("array<int>")
     *
     * @var int[]
     */
    private $pickupPointIds;

    /**
     * @JMS\Type("array<YDeliverySDK\Responses\Types\DeliveryOption\Shipment>")
     *
     * @var Shipment[]
     */
    private $shipments = [];

    /**
     * @JMS\Type("array<YDeliverySDK\Responses\Types\DeliveryOption\Service>")
     *
     * @var Service[]
     */
    private $services;

    /**
     * @JMS\Type("array<string>")
     *
     * @var string[]
     */
    private $tags = [];

    /**
     * Идентификатор тарифа.
     */
    public function getTariffId(): int
    {
        return $this->tariffId;
    }

    /**
     * Название тарифа.
     */
    public function getTariffName(): ?string
    {
        return $this->tariffName;
    }

    /**
     * Информация о стоимости заказа.
     */
    public function getCost(): Cost
    {
        return $this->cost;
    }

    /**
     * Информация о доставке.
     */
    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }

    /**
     * Идентификаторы пунктов выдачи заказов (для доставки в ПВЗ и почтой).
     *
     * @return int[]
     */
    public function getPickupPointIds()
    {
        return $this->pickupPointIds;
    }

    /**
     * Информация об отгрузке.
     *
     * @return Shipment[]
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * Дополнительные услуги.
     *
     * @return Service[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Метки варианта доставки.
     *
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }
}
