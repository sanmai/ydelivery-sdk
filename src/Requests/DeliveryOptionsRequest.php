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
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Requests\Types\Address;
use YDeliverySDK\Requests\Types\Cost;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Requests\Types\Shipment;
use YDeliverySDK\Responses\DeliveryOptionsResponse;

/**
 * DeliveryOptionsRequest.
 *
 * @see https://yandex.ru/dev/delivery-3/doc/dg/reference/put-delivery-options-docpage/
 *
 * @property-write int $senderId
 * @property-read Address $from
 * @property-read Address $to
 * @property-read Dimensions $dimensions
 * @property-write string $deliveryType
 * @property-read Shipment $shipment
 * @property-read Cost $cost
 * @property-write int $tariffId
 */
final class DeliveryOptionsRequest implements JsonRequest
{
    use RequestCore;
    use PropertyWrite;
    use ObjectPropertyRead;

    private const METHOD = 'PUT';
    private const ADDRESS = '/delivery-options';
    private const RESPONSE = DeliveryOptionsResponse::class;

    /**
     * Тип доставки: курьером.
     *
     * @var string
     */
    public const DELIVERY_TYPE_COURIER = 'COURIER';

    /**
     * Тип доставки: в пункт выдачи.
     *
     * @var string
     */
    public const DELIVERY_TYPE_PICKUP = 'PICKUP';

    /**
     * Тип доставки: по почте.
     *
     * @var string
     */
    public const DELIVERY_TYPE_POST = 'POST';

    /**
     * Идентификатор магазина.
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    private $senderId;

    /**
     * Информация об отправителе.
     *
     * @JMS\Type("YDeliverySDK\Requests\Types\Address")
     *
     * @var Address
     */
    private $from;

    /**
     * Информация о получателе.
     *
     * @JMS\Type("YDeliverySDK\Requests\Types\Address")
     *
     * @var Address
     */
    private $to;

    /**
     * Вес и габариты отправления.
     *
     * @JMS\Type("YDeliverySDK\Requests\Types\Dimensions")
     *
     * @var Dimensions
     */
    private $dimensions;

    /**
     * Тип доставки.
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    private $deliveryType;

    /**
     * Информация об отгрузке.
     *
     * @JMS\Type("YDeliverySDK\Requests\Types\Shipment")
     *
     * @var Shipment
     */
    private $shipment;

    /**
     * Информация о стоимости заказа.
     *
     * @JMS\Type("YDeliverySDK\Requests\Types\Cost")
     *
     * @var Cost
     */
    private $cost;

    /**
     * Идентификатор тарифа.
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    private $tariffId;

    /**
     * @phan-suppress PhanAccessReadOnlyMagicProperty
     */
    public function __construct(
        ?Address $from = null,
        ?Address $to = null,
        ?Dimensions $dimensions = null,
        ?Shipment $shipment = null,
        ?Cost $cost = null
    ) {
        $this->from = $from ?? new Address();
        $this->to = $to ?? new Address();
        $this->dimensions = $dimensions ?? new Dimensions();
        $this->shipment = $shipment ?? new Shipment();
        $this->cost = $cost ?? new Cost();
    }
}
