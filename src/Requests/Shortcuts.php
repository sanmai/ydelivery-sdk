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

use CommonSDK\Contracts;
use YDeliverySDK\Client;
use YDeliverySDK\Responses;
use YDeliverySDK\Responses\OrdersSearchResponseIterator;

/**
 * @method Responses\DeliveryServicesResponse|Responses\Types\DeliveryService[] sendDeliveryServicesRequest(DeliveryServicesRequest $request)
 * @method Responses\PostalCodeResponse|Responses\Types\PostalCode[]            sendPostalCodeRequest(PostalCodeRequest $request)
 * @method Responses\LocationResponse|Responses\Types\Location[]                sendLocationRequest(LocationRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendWithdrawIntervalsRequest(WithdrawIntervalsRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendImportIntervalsRequest(ImportIntervalsRequest $request)
 * @method Responses\DeliveryOptionsResponse|Responses\Types\DeliveryOption[]   sendDeliveryOptionsRequest(DeliveryOptionsRequest $request)
 * @method Responses\OrderResponse                                              sendCreateOrderRequest(CreateOrderRequest $request)
 * @method Responses\SubmitOrderResponse|Responses\Types\SubmittedOrder[]       sendSubmitOrderRequest(SubmitOrderRequest $request)
 * @method Responses\OrdersSearchResponse|Responses\Types\Order[]               sendOrdersSearchRequest(OrdersSearchRequest $request)
 * @method Contracts\Response                                                   sendDeleteOrderRequest(DeleteOrderRequest $request)
 *
 * @phan-file-suppress PhanTypeMismatchArgument
 */
trait Shortcuts
{
    /**
     * @return Responses\LocationResponse|Responses\Types\Location[]
     */
    public function makeLocationRequest(string $term)
    {
        $request = new LocationRequest();
        $request->term = $term;

        return $this->sendLocationRequest($request);
    }

    /**
     * @return Responses\PostalCodeResponse|Responses\Types\PostalCode[]
     */
    public function makePostalCodeRequest(string $address)
    {
        $request = new PostalCodeRequest();
        $request->address = $address;

        return $this->sendPostalCodeRequest($request);
    }

    /**
     * @return Contracts\Response
     */
    public function makeDeleteOrderRequest(int $orderId)
    {
        $request = new DeleteOrderRequest($orderId);

        return $this->sendDeleteOrderRequest($request);
    }

    /**
     * @return Responses\OrdersSearchResponseIterator|Responses\Types\Order[]
     * @psalm-return Responses\OrdersSearchResponseIterator
     */
    public function searchOrders(OrdersSearchRequest $request)
    {
        /** @var Client $this */
        return new OrdersSearchResponseIterator($this, $request);
    }
}
