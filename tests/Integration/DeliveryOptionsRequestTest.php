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

use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\Types\Address;
use YDeliverySDK\Requests\Types\Cost;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Requests\Types\Shipment;

if (false) {
    include 'examples/080_DeliveryOptionsRequest.php';
}

/**
 * @covers \YDeliverySDK\Requests\DeliveryOptionsRequest
 *
 * @group integration
 */
final class DeliveryOptionsRequestTest extends TestCase
{
    public function test_successful_request()
    {
        $request = new DeliveryOptionsRequest();
        $request->setSenderId($this->getShopId());

        $from = new Address();
        $from->setLocation('Москва, Красная пл., 1');
        $from->setGeoId(890567);

        // $request->setFrom($from);

        $to = new Address();
        $to->setLocation('Новосибирск, Красный пр., 36');
        //$to->setGeoId(111111);
        //$to->setPickupPointIds([222222, 333333]);

        $request->setTo($to);

        $dimensions = new Dimensions();
        $dimensions->setLength(10);
        $dimensions->setWidth(20);
        $dimensions->setHeight(30);
        $dimensions->setWeight(5.25);

        $request->setDimensions($dimensions);

        $request->setDeliveryType($request::DELIVERY_TYPE_POST);

        $shipment = new Shipment();
        $shipment->setDate(new \DateTime('next Monday'));
        $shipment->setType($shipment::TYPE_IMPORT);
        //$shipment->setPartnerId(2222222);
        //$shipment->setWarehouseId(11111111);
        //$shipment->setIncludeNonDefault(true);

        $request->setShipment($shipment);

        $cost = new Cost();
        $cost->setAssessedValue(500);
        $cost->setItemsSum(1000);
        $cost->setManualDeliveryForCustomer(750);
        $cost->setFullyPrepaid(true);

        $request->setCost($cost);

        //$request->setTariffId(444444);

        $resp = $this->getClient()->sendDeliveryOptionsRequest($request);

        $this->assertGreaterThan(0, \count($resp));

        foreach ($resp as $value) {
            $this->assertGreaterThan(0, $value->getTariffId());
            $value->getTariffName();

            [
                $value->getTariffId(),
                $value->getTariffName(),
                $value->getCost()->getDelivery(),
                $value->getCost()->getDeliveryForCustomer(),
                $value->getCost()->getDeliveryForSender(),
            ];

            [
                $value->getDelivery()->getType(),
                $value->getDelivery()->getPartner()->getId(),
                $value->getDelivery()->getPartner()->getName(),
                $value->getDelivery()->getCalculatedDeliveryDateMin()->format('Y-m-d'),
                $value->getDelivery()->getCalculatedDeliveryDateMax()->format('Y-m-d'),
            ];

            foreach ($value->getServices() as $service) {
                [
                    $service->getName(),
                    $service->getCode(),
                    $service->getCost(),
                    $service->getCustomerPay(),
                    $service->getEnabledByDefault(),
                ];
            }
        }
    }
}
