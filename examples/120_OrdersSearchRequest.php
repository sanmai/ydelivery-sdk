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
use YDeliverySDK\Requests\OrdersSearchRequest;

include_once 'vendor/autoload.php';

$logger = new DebuggingLogger();

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($_SERVER['YANDEX_DELIVERY_TOKEN'] ?? '');
$builder->setLogger($logger);
/** @var \YDeliverySDK\Client $client */
$client = $builder->build();

$request = new OrdersSearchRequest([
    (int) $_SERVER['YANDEX_SHOP_ID'],
]);
$request->term = '+79266056128';
$request->size = 2;

$logger->addFile('orders-search-request.json');
$logger->addFile('orders-search-response.json');

$orders = $client->searchOrders($request);

if ($orders->hasErrors()) {
    // Обрабатываем ошибки
    foreach ($orders->getMessages() as $message) {
        if ($message->getErrorCode() !== '') {
            // Это ошибка
            echo "{$message->getErrorCode()}: {$message->getMessage()}\n";
        }
    }

    return;
}

foreach ($orders as $order) {
    echo "{$order->id}\t{$order->status}\t{$order->comment}\n";

    if (\strpos($order->comment, 'тестовый заказ') === false) {
        continue;
    }

    if ($order->status === 'CANCELLED') {
        continue;
    }

    $response = $client->makeDeleteOrderRequest($order->id);

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
}
