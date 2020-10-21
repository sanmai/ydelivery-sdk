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

namespace YDeliverySDK\Requests\Templates;

use CommonSDK\Concerns\ObjectPropertyRead;
use CommonSDK\Concerns\PropertyWrite;
use CommonSDK\Contracts\JsonRequest;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Common;
use YDeliverySDK\Requests\Templates\OrderRequest\Place;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Requests\Types\Shipment;
use YDeliverySDK\Responses\OrderResponse;

/**
 * Основа для методов создания или изменения заказов.
 *
 * @property-write int $senderId Идентификатор магазина.
 * @property-write string $externalId Идентификатор заказа в системе партнера.
 * @property-write string $comment
 * @property-write string $deliveryType Тип доставки (COURIER — курьером, PICKUP — в пункт выдачи, POST — на почту).
 * @property-read OrderRequest\Recipient $recipient Данные о получателе.
 * @property-read OrderRequest\Cost $cost
 * @property-read OrderRequest\DeliveryOption $deliveryOption
 * @property-read Shipment $shipment
 */
abstract class OrderRequest implements JsonRequest
{
    use PropertyWrite;
    use ObjectPropertyRead;

    /**
     * Тип доставки — курьером
     */
    public const DELIVERY_TYPE_COURIER = 'COURIER';

    /**
     * Тип доставки — на почту.
     */
    public const DELIVERY_TYPE_POST = 'POST';

    /**
     * Тип доставки — в пункт выдачи.
     */
    public const DELIVERY_TYPE_PICKUP = 'PICKUP';

    protected const RESPONSE = OrderResponse::class;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $senderId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $externalId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $comment;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $deliveryType;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Templates\OrderRequest\Recipient")
     *
     * @var OrderRequest\Recipient
     */
    protected $recipient;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Templates\OrderRequest\Cost")
     *
     * @var OrderRequest\Cost
     */
    protected $cost;

    /**
     * @JMS\Type("array<YDeliverySDK\Requests\Templates\OrderRequest\Contact>")
     *
     * @var OrderRequest\Contact[]
     */
    protected $contacts = [];

    /**
     * @JMS\Type("YDeliverySDK\Common\DeliveryOption")
     *
     * @var Common\DeliveryOption
     */
    protected $deliveryOption;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Types\Shipment")
     *
     * @var Shipment
     */
    protected $shipment;

    /**
     * @JMS\Type("array<YDeliverySDK\Requests\Templates\OrderRequest\Place>")
     *
     * @var Place[]
     */
    protected $places = [];

    /**
     * @phan-suppress PhanAccessReadOnlyMagicProperty
     */
    public function __construct(
        ?OrderRequest\DeliveryOption $deliveryOption = null,
        ?OrderRequest\Recipient $recipient = null,
        ?OrderRequest\Cost $cost = null,
        array $contacts = [],
        ?Shipment $shipment = null,
        array $places = []
    ) {
        $this->deliveryOption = $deliveryOption ?? new OrderRequest\DeliveryOption();
        $this->recipient = $recipient ?? new OrderRequest\Recipient();
        $this->cost = $cost ?? new OrderRequest\Cost();
        $this->contacts = $contacts;
        $this->shipment = $shipment ?? new Shipment();
        $this->places = $places;
    }

    public function addContact(string $type = OrderRequest\Contact::TYPE_RECIPIENT): OrderRequest\Contact
    {
        $contact = new OrderRequest\Contact();

        $this->contacts[] = $contact;

        return $contact;
    }

    public function addPlace(?Dimensions $dimensions = null, array $items = []): Place
    {
        $place = new Place($dimensions, $items);

        $this->places[] = $place;

        return $place;
    }
}
