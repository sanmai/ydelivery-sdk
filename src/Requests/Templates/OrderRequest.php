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
use YDeliverySDK\Requests\Templates\OrderRequest\Contact;
use YDeliverySDK\Requests\Templates\OrderRequest\Cost;
use YDeliverySDK\Requests\Templates\OrderRequest\DeliveryOption;
use YDeliverySDK\Requests\Templates\OrderRequest\Place;
use YDeliverySDK\Requests\Templates\OrderRequest\Recipient;
use YDeliverySDK\Requests\Templates\OrderRequest\Shipment;
use YDeliverySDK\Responses\OrderResponse;

/**
 * Основа для методов создания или изменения заказов.
 *
 * @property-write int $senderId Идентификатор магазина.
 * @property-write string $externalId Идентификатор заказа в системе партнера.
 * @property-write string $comment
 * @property-write string $deliveryType Тип доставки (COURIER — курьером, PICKUP — в пункт выдачи, POST — на почту).
 * @property-read Recipient $recipient Данные о получателе.
 * @property-read Cost $cost
 * @property-read DeliveryOption $deliveryOption
 * @property-read Shipment $shipment
 */
abstract class OrderRequest implements JsonRequest
{
    use PropertyWrite;
    use ObjectPropertyRead;

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
     * @var Recipient
     */
    protected $recipient;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Templates\OrderRequest\Cost")
     *
     * @var Cost
     */
    protected $cost;

    /**
     * @JMS\Type("array<YDeliverySDK\Requests\Templates\OrderRequest\Contact>")
     *
     * @var Contact[]
     */
    protected $contacts = [];

    /**
     * @JMS\Type("YDeliverySDK\Requests\Templates\OrderRequest\DeliveryOption")
     *
     * @var DeliveryOption
     */
    protected $deliveryOption;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Templates\OrderRequest\Shipment")
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
        ?Recipient $recipient = null,
        ?Cost $cost = null,
        array $contacts = [],
        ?DeliveryOption $deliveryOption = null,
        ?Shipment $shipment = null,
        array $places = []
    ) {
        $this->recipient = $recipient ?? new Recipient();
        $this->cost = $cost ?? new Cost();
        $this->contacts = $contacts;
        $this->deliveryOption = $deliveryOption ?? new DeliveryOption();
        $this->shipment = $shipment ?? new Shipment();
        $this->places = $places;
    }

    public function addContact(): Contact
    {
        $contact = new Contact();

        $this->contacts[] = $contact;

        return $contact;
    }

    public function addPlace(): Place
    {
        $place = new Place();

        $this->places[] = $place;

        return $place;
    }
}
