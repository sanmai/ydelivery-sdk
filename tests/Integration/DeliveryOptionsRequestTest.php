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

namespace Tests\YDeliverySDK\Integration;

use DateTime;
use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\Types\Shipment;
use YDeliverySDK\Responses\DeliveryOptionsResponse;

if (false) {
    include 'examples/080_DeliveryOptionsRequest.php';
}

/**
 * @covers \YDeliverySDK\Requests\DeliveryOptionsRequest
 * @covers \YDeliverySDK\Responses\DeliveryOptionsResponse
 *
 * @group integration
 */
final class DeliveryOptionsRequestTest extends TestCase
{
    public function test_successful_request()
    {
        $request = new DeliveryOptionsRequest();
        $request->senderId = $this->getShopId();

        $request->from->location = 'Москва, Красная пл., 1';

        $request->to->location = 'Новосибирск, Красный пр., 36';
        //$request->to->geoId = 4444444;
        //$request->to->pickupPointIds = [11111, 222222];

        $request->dimensions->length = 10;
        $request->dimensions->width = 20;
        $request->dimensions->height = 30;
        $request->dimensions->weight = 5.25;

        $request->deliveryType = $request::DELIVERY_TYPE_POST;

        $request->shipment->date = new DateTime('next Monday');
        $request->shipment->type = Shipment::TYPE_IMPORT;
        //$request->shipment->partnerId = 1111111111;
        //$request->shipment->warehouseId = 2222222222;
        //$request->shipment->includeNonDefault = true;

        $request->cost->assessedValue = 500;
        $request->cost->itemsSum = 1000;
        $request->cost->manualDeliveryForCustomer = 750;
        $request->cost->fullyPrepaid = true;

        // $request->tariffId = 333333333;

        $response = $this->getClient()->sendDeliveryOptionsRequest($request);

        $this->assertInstanceOf(DeliveryOptionsResponse::class, $response);

        $this->assertGreaterThan(0, \count($response));

        foreach ($response as $value) {
            $this->assertGreaterThan(0, $value->tariffId);
            $value->tariffName;

            [
                $value->tariffId,
                $value->tariffName,
                $value->cost->delivery,
                $value->cost->deliveryForCustomer,
                $value->cost->deliveryForSender,
            ];

            [
                $value->delivery->type,
                $value->delivery->partner->id,
                $value->delivery->partner->name,
                $value->delivery->calculatedDeliveryDateMin->format('Y-m-d'),
                $value->delivery->calculatedDeliveryDateMax->format('Y-m-d'),
            ];

            foreach ($value->services as $service) {
                [
                    $service->name,
                    $service->code,
                    $service->cost,
                    $service->customerPay,
                    $service->enabledByDefault,
                ];
            }
        }
    }
}
