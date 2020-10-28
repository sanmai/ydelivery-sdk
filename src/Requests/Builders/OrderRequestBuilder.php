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

namespace YDeliverySDK\Requests\Builders;

use YDeliverySDK\Requests\Templates\OrderRequest;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Responses\Types\DeliveryOption;
use YDeliverySDK\Responses\Types\Location;
use YDeliverySDK\Responses\Types\PostalCode;

/**
 * OrderRequestRequest builder.
 *
 * @psalm-template T of OrderRequest
 *
 * @see https://yandex.ru/dev/delivery-3/doc/dg/reference/post-orders.html
 */
final class OrderRequestBuilder
{
    /**
     * @var OrderRequest
     * @psalm-var T
     */
    private $request;

    /** @var DeliveryOption */
    private $deliveryOption;

    /** @var Location */
    private $location;

    /** @var Dimensions */
    private $dimensions;

    /** @var int */
    private $shipment = 0;

    /** @var PostalCode|null */
    private $postalCode;

    /**
     * @psalm-param T $request
     */
    public function __construct(DeliveryOption $deliveryOption, Location $location, OrderRequest $request)
    {
        $this->request = $request;
        $this->deliveryOption = $deliveryOption;
        $this->location = $location;
    }

    public function setPostalCode(PostalCode $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @psalm-return T
     *
     * @return OrderRequest
     */
    public function build()
    {
        $this->request->deliveryType = $this->deliveryOption->delivery->type;
        $this->request->shipment->date = $this->deliveryOption->shipments[$this->shipment]->date;
        $this->request->shipment->type = $this->deliveryOption->shipments[$this->shipment]->type;
        $this->request->shipment->partnerTo = $this->deliveryOption->shipments[$this->shipment]->partner->id;
        // $this->request->shipment->warehouseFrom = ... ?
        // $this->request->shipment->warehouseTo = ... ?

        $this->request->deliveryOption->tariffId = $this->deliveryOption->tariffId;
        $this->request->deliveryOption->delivery = $this->deliveryOption->cost->delivery;
        $this->request->deliveryOption->deliveryForCustomer = $this->deliveryOption->cost->deliveryForCustomer;

        // $this->request->deliveryOption->type = 'PICKUP';
        $this->request->deliveryOption->partnerId = $this->deliveryOption->delivery->partner->id;
        $this->request->deliveryOption->calculatedDeliveryDateMin = $this->deliveryOption->delivery->calculatedDeliveryDateMin;
        $this->request->deliveryOption->calculatedDeliveryDateMax = $this->deliveryOption->delivery->calculatedDeliveryDateMax;

        //$this->request->recipient->pickupPointId = ... ?
        $this->request->recipient->address->geoId = $this->location->geoId;
        foreach ($this->location->addressComponents as $component) {
            $this->request->recipient->address->{$component->kind} = $component->name;
        }

        if ($this->postalCode !== null) {
            $this->request->recipient->address->postalCode = $this->postalCode->postalCode;
        }

        return $this->request;
    }
}
