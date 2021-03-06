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

include_once 'vendor/autoload.php';

$logger = new DebuggingLogger();

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($_SERVER['YANDEX_DELIVERY_TOKEN'] ?? '');
$builder->setLogger($logger);
/** @var \YDeliverySDK\Client $client */
$client = $builder->build();

$order = $client->getOrder((int) $argv[1]);

$logger->addFile('order-status-request.json');
$logger->addFile('order-status-response.json');

$statuses = $client->getOrderStatuses($order->id);

echo "Order ID: {$statuses->id}\n";

foreach ($statuses as $status) {
    echo \join("\t", [
        $statuses->id,
        $status->code,
        $status->description,
        $status->datetime->format('r'),
    ]), "\n";
}

if (!$order->hasLabel) {
    return;
}

$response = $client->getLabel($order->id);

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

\file_put_contents("{$order->deliveryServiceExternalId}.pdf", $response->getBody());

\passthru("ls -l {$order->deliveryServiceExternalId}.pdf");
