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
use YDeliverySDK\Requests\CreateOrderRequest;
use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\UpdateOrderRequest;

include_once 'vendor/autoload.php';

$logger = new DebuggingLogger();

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($_SERVER['YANDEX_DELIVERY_TOKEN'] ?? '');
$builder->setLogger($logger);
/** @var \YDeliverySDK\Client $client */
$client = $builder->build();

/**
 * Получим данные по адресу получателя.
 */
$response = $client->makeLocationRequest('Новосибирская область, Новосибирск');

\var_dump(\count($response));

foreach ($response as $location) {
    echo "{$location->geoId}\t{$location->address}\n";

    foreach ($location->addressComponents as $component) {
        echo "- {$component->kind}: {$component->name}\n";
    }
    break;
}

/**
 * Получим почтовый индекс.
 */
$response = $client->makePostalCodeRequest('Новосибирская область, Новосибирск, ул. Державина, 5');

\var_dump(\count($response));

foreach ($response as $postalCode) {
    echo "$postalCode\n";

    break;
}

/**
 * Получим тарифы.
 */
$request = new DeliveryOptionsRequest();

$request->senderId = $_SERVER['YANDEX_SHOP_ID'];

$request->from->geoId = $_SERVER['YANDEX_SENDER_GEOID'];
$request->to->location = 'Новосибирск, ул. Державина, 5';

$request->dimensions->length = 10;
$request->dimensions->width = 20;
$request->dimensions->height = 30;
$request->dimensions->weight = 1.25;
$dimensions = $request->dimensions;

$request->deliveryType = $request::DELIVERY_TYPE_COURIER;

// $request->shipment->date = new DateTime('next Monday');
$request->shipment->type = $request->shipment::TYPE_WITHDRAW;

$request->cost->assessedValue = 1000;
$request->cost->itemsSum = 1000;
$request->cost->fullyPrepaid = true;

// $request->tariffId = 333333333;

$logger->addFile('delivery-options-request.json');
$logger->addFile('delivery-options-response.json');

$deliveryMethods = $client->sendDeliveryOptionsRequest($request);

if (!\count($deliveryMethods)) {
    return;
}

$deliveryMethod = $deliveryMethods->getFirstTagged($deliveryMethods::TAG_CHEAPEST);

if (!$deliveryMethod) {
    return;
}

/**
 * Создадим заказ без данных.
 */
$request = new CreateOrderRequest();
$request->deliveryType = $request::DELIVERY_TYPE_COURIER;
$request->comment = 'Пустой тестовый заказ.';
$request->senderId = (int) $_SERVER['YANDEX_SHOP_ID'];
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

echo "\n\nCreated: {$response->id}\n\n";

$order = $client->getOrder($response->id);

echo "\n\nLoaded: {$order->id} ({$order->comment})\n\n";

/**
 * Наполним заказ данными.
 */
$requestBuilder = UpdateOrderRequest::builder($order->id, $deliveryMethod, $location);
$requestBuilder->setPostalCode($postalCode);
$request = $requestBuilder->build();

$request->shipment->warehouseFrom = (int) $_SERVER['YANDEX_WAREHOUSE_ID'];
$request->senderId = (int) $_SERVER['YANDEX_SHOP_ID'];
$request->comment = 'Доставки не будет - тестовый заказ';
// $request->externalId = '426';

$request->recipient->firstName = 'Василий';
$request->recipient->lastName = 'Юрочкин';
$request->recipient->phone = '+79266056128';

$request->recipient->address->apartment = '43';
$request->recipient->address->house = '5';
$request->recipient->address->housing = '';
$request->recipient->address->street = 'ул. Державина';
//$request->recipient->pickupPointId = 10000018299;

$place = $request->addPlace($dimensions);
// $place->externalId = '427';
$item = $place->addItem();
$item->externalId = '428';
$item->name = 'HELP';
$item->count = 2;
$item->price = 500;
$item->assessedValue = 500;
// $item->tax = $item::TAX_NO_VAT;

$request->cost->assessedValue = 1000;
$request->cost->fullyPrepaid = true;
$request->cost->paymentMethod = $request->cost::PAYMENT_METHOD_PREPAID;

$contact = $request->addContact();

$contact->phone = '+79266056128';
$contact->firstName = 'Василий';
$contact->lastName = 'Юрочкин';

$logger->addFile('update-order-request.json');
$logger->addFile('update-order-response.json');

$response = $client->sendUpdateOrderRequest($request);

echo "\n\nUpdated: {$response->id}\n\n";
