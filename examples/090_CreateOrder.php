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
use YDeliverySDK\Requests;

include_once 'vendor/autoload.php';

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($_SERVER['YANDEX_DELIVERY_TOKEN'] ?? '');
$builder->setLogger(new DebuggingLogger());
/** @var \YDeliverySDK\Client $client */
$client = $builder->build();

$request = new Requests\CreateOrderRequest();
$request->deliveryType = $request::DELIVERY_TYPE_COURIER;
$request->senderId = (int) $_SERVER['YANDEX_SHOP_ID'];

$request->shipment->date = '2020-02-12';
$request->shipment->warehouseFrom = (int) $_SERVER['YANDEX_WAREHOUSE_ID'];
$request->shipment->type = $request->shipment::TYPE_IMPORT;
$request->shipment->warehouseTo = 10000816227;
$request->shipment->partnerTo = 107;

$request->recipient->firstName = 'Василий';
$request->recipient->lastName = 'Юрочкин';
$request->recipient->phone = '+79266056128';

$request->recipient->address->apartment = '43';
$request->recipient->address->country = 'Россия';
$request->recipient->address->geoId = 213;
$request->recipient->address->house = '8';
$request->recipient->address->housing = '';
$request->recipient->address->locality = 'Москва';
$request->recipient->address->region = 'Москва';
$request->recipient->address->street = 'Коктебельская улица';
$request->recipient->pickupPointId = 10000018299;

$place = $request->addPlace();

$place->externalId = '427';
$place->dimensions->length = 10;
$place->dimensions->width = 20;
$place->dimensions->height = 30;
$place->dimensions->weight = 0.3;

$item = $place->addItem();

$item->externalId = '428';
$item->name = '2 товар';
$item->count = 1;
$item->price = 1467;
$item->assessedValue = 1467;
$item->tax = $item::TAX_NO_VAT;

$item->dimensions->length = 10;
$item->dimensions->width = 20;
$item->dimensions->height = 30;
$item->dimensions->weight = 0.3;

$request->externalId = '426';
$request->deliveryType = $request::DELIVERY_TYPE_PICKUP;
$request->comment = 'Доставки не будет - тестовый заказ';

$request->deliveryOption->tariffId = 100040;

$request->deliveryOption->tariffId = 100040;
$request->deliveryOption->delivery = 198.00;
$request->deliveryOption->deliveryForCustomer = 369.802;
$request->deliveryOption->type = $request::DELIVERY_TYPE_PICKUP;
$request->deliveryOption->partnerId = 107;
$request->deliveryOption->calculatedDeliveryDateMin = '2020-02-13';
$request->deliveryOption->calculatedDeliveryDateMax = '2020-02-13';

$service = $request->deliveryOption->addService();
$service->name = 'Доставка';
$service->code = 'DELIVERY';
$service->cost = 188.00;
$service->customerPay = true;
$service->enabledByDefault = true;

$service = $request->deliveryOption->addService();
$service->name = 'Услуга Объявленная ценность';
$service->code = 'INSURANCE';
$service->cost = 7.335;
$service->customerPay = true;
$service->enabledByDefault = true;

$service = $request->deliveryOption->addService();
$service->name = 'Возврат';
$service->code = 'RETURN';
$service->cost = 141.0000;
$service->customerPay = false;
$service->enabledByDefault = false;

$service = $request->deliveryOption->addService();
$service->name = 'Сортировка возврата';
$service->code = 'RETURN_SORT';
$service->cost = 20;
$service->customerPay = false;
$service->enabledByDefault = false;

$service = $request->deliveryOption->addService();
$service->name = 'Вознаграждение за перечисление денежных средств';
$service->code = 'CASH_SERVICE';
$service->cost = 33.439;
$service->customerPay = false;
$service->enabledByDefault = true;

$request->cost->assessedValue = 1467;
$request->cost->fullyPrepaid = false;
$request->cost->manualDeliveryForCustomer = 500;
$request->cost->paymentMethod = $request->cost::PAYMENT_METHOD_CASH;

$contact = $request->addContact();

$contact->type = $contact::TYPE_RECIPIENT;
$contact->phone = '+79266056128';
$contact->firstName = 'Василий';
$contact->lastName = 'Юрочкин';

$response = $client->sendCreateOrderRequest($request);

if ($response->hasErrors()) {
    // Обрабатываем ошибки
    foreach ($response->getMessages() as $message) {
        if ($message->getErrorCode() !== '') {
            // Это ошибка
            echo "{$message->getErrorCode()}: {$message->getMessage()}\n";
        }
    }

    return;
}

\var_dump($response->id);
