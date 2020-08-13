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

use Tests\YDeliverySDK\Integration\DebuggingLogger;
use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\Types\Address;
use YDeliverySDK\Requests\Types\Cost;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Requests\Types\Shipment;

include_once 'vendor/autoload.php';

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($_SERVER['YANDEX_DELIVERY_TOKEN'] ?? '');
$builder->setLogger(new DebuggingLogger());
$client = $builder->build();

$request = new DeliveryOptionsRequest();
$request->setSenderId($_SERVER['YANDEX_SHOP_ID']);

$from = new Address();
$from->setLocation('Москва, Красная пл., 1');
$from->setGeoId(890567);

// $request->setFrom($from);

$to = new Address();
$to->setLocation('Новосибирск, Красный пр., 36');
//$to->setGeoId(4444444);
//$to->setPickupPointIds([11111, 222222]);

$request->setTo($to);

$dimensions = new Dimensions();
$dimensions->setLength(10);
$dimensions->setWidth(20);
$dimensions->setHeight(30);
$dimensions->setWeight(5.25);

$request->setDimensions($dimensions);

$request->setDeliveryType($request::DELIVERY_TYPE_POST);

$shipment = new Shipment();
$shipment->setDate(new DateTime('next Monday'));
$shipment->setType($shipment::TYPE_IMPORT);
//$shipment->setPartnerId(1111111111);
// $shipment->setWarehouseId(2222222222);
//$shipment->setIncludeNonDefault(true);

$request->setShipment($shipment);

$cost = new Cost();
$cost->setAssessedValue(500);
$cost->setItemsSum(1000);
$cost->setManualDeliveryForCustomer(750);
$cost->setFullyPrepaid(true);

$request->setCost($cost);

// $request->setTariffId(333333333);

$resp = $client->sendDeliveryOptionsRequest($request);

\var_dump(\count($resp));

foreach ($resp as $value) {
    echo \join("\t", [
        $value->getTariffId(),
        $value->getTariffName() ?? 'Без названия',
        $value->getCost()->getDelivery(),
        $value->getCost()->getDeliveryForCustomer(),
        $value->getCost()->getDeliveryForSender(),
    ]), "\n";

    echo \join("\t", [
        $value->getDelivery()->getType(),
        $value->getDelivery()->getPartner()->getId(),
        $value->getDelivery()->getPartner()->getName(),
        $value->getDelivery()->getCalculatedDeliveryDateMin()->format('Y-m-d'),
        $value->getDelivery()->getCalculatedDeliveryDateMax()->format('Y-m-d'),
    ]), "\n";

    foreach ($value->getServices() as $service) {
        echo "\t- ";
        echo \join("\t", [
            $service->getName(),
            $service->getCode(),
            $service->getCost(),
            $service->getCustomerPay(),
            $service->getEnabledByDefault(),
        ]), "\n";
    }
}
