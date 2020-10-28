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

use CommonSDK\Concerns\RequestCore;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Requests\Builders\OrderRequestBuilder;
use YDeliverySDK\Requests\Templates\OrderRequest;
use YDeliverySDK\Responses\OrderResponse;
use YDeliverySDK\Responses\Types as ResponsesTypes;

/**
 * UpdateOrderRequest.
 *
 * @see https://yandex.ru/dev/delivery-3/doc/dg/reference/put-orders-id.html
 */
final class UpdateOrderRequest extends OrderRequest
{
    use RequestCore;

    private const METHOD = 'PUT';
    private const ADDRESS = '/orders/%s';

    protected const RESPONSE = OrderResponse::class;

    /**
     * @JMS\Exclude
     *
     * @var int
     */
    private $id;

    /**
     * @phan-suppress PhanAccessReadOnlyMagicProperty
     */
    public function __construct(
        int $id,
        ?OrderRequest\DeliveryOption $deliveryOption = null,
        ?OrderRequest\Recipient $recipient = null,
        ?OrderRequest\Cost $cost = null,
        array $contacts = [],
        ?Types\Shipment $shipment = null,
        array $places = []
    ) {
        parent::__construct($deliveryOption, $recipient, $cost, $contacts, $shipment, $places);

        $this->id = $id;
    }

    /**
     * @return OrderRequestBuilder
     * @psalm-return OrderRequestBuilder<UpdateOrderRequest>
     */
    public static function builder(
        int $id,
        ResponsesTypes\DeliveryOption $deliveryOption,
        ResponsesTypes\Location $location
    ) {
        return new OrderRequestBuilder($deliveryOption, $location, new static(
            $id,
            new OrderRequest\DeliveryOption($deliveryOption->services)
        ));
    }

    public function getAddress(): string
    {
        return \sprintf(static::ADDRESS, $this->id);
    }
}
