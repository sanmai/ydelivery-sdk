

Перед вами SDK для [интеграции с обновлённой Яндекс.Доставкой](https://yandex.ru/support/delivery-3/register.html) с открытым [исходным кодом](https://github.com/sanmai/ydelivery-sdk) согласно [документации на API](https://yandex.ru/support/delivery-3/api.html).

## Установка  {: #install }

```bash
composer require sanmai/ydelivery-sdk
```
Для работы SDK нужен PHP 7.3 или выше. Работа протестирована под PHP 7.3, 7.4, 8.0, 8.1.

### Гарантии обратной совместимости {: #backward-compatibility }

При разработке этой библиотеки большое внимание уделяется обратной совместимости API в пределах основной версии. Если вы установили когда-то версию ветки 0.1, например 0.1.7, то после обновления до 0.1.8 или даже до 0.1.12 вы можете рассчитывать что весь ваш код будет работать точно так же, как раньше, или лучше, без необходимости что-то менять, при условии, конечно, что API самой Яндекс.Доставки не поменялось. Такого же принципа работы с версиями [по умолчанию придерживается Composer](https://getcomposer.org/doc/articles/versions.md#caret-version-range-).

Гарантии обратной совместимости в части возвращаемых типов распостраняются только на имплементируемые ими интерфейсы. Если вы получали объект имплементирующий `CommonSDK\Contracts\Response`, то такой же объёкт вы продолжите получать. Если у возвращенного объёкта был какой-то метод, то такой же метод будет у объекта в следующей неосновной версии. Конкретный тип может быть другим, рассчитывать на это не нужно, проверять принадлежность конкретному типу также не следует. [Как проверять ответы на ошибки.](#hasErrors)

Такие строгие гарантии обратной совместимости API были бы невозможны без идущей рука об руку с ними минимизации точек для расширения API: наследование для большинства классов не только не предусмотрено, но и просто невозможно. Впрочем, для удобства композиции есть необходимые интерфейсы. Мы исходим из того, что добавить ещё интерфейсы проблемы не представляет, новые интерфейсы не ломают обратную совместимость.

После выхода версии 1.0 обратная совместимость будет поддерживаться в пределах мажорной версии.

## Общие замечания

Что нужно иметь ввиду, прежде чем вы начнёте разработку:

- На момент написания этих строк у Яндекс.Доставки нет тестового окружения. Все заказы будут настоящие.
- Документация не описывает какие поля важны и какие необходимы при создании заказа. Может быть вам поможет обращение в поддержку? Сложно сказать. Примерно половина обращений автора заканчивались ответами-отписками, с чётким ощущением что сотрудник поддержки не потрудился вникнуть в проблему. Впечатления от такой "поддержки" самые отрицательные.
- Успешное создание заказа не гарантирует отсутствие ошибок при подтверждении. Можно не увидеть никаких ошибок при создании заказа и получить кучу при подтверждении.
- Существование полей в каждом ответе не гарантируется. Готовьтесь получать `null` вместо ожидаемого объекта или строки. И пустую строку вместо `null` в другом ответе по этому же полю. Объяснения этому в документации нет.

## Инициализация {: #ClientBuilder }

```php
require_once 'vendor/autoload.php';

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($token);
// Есть и другие методы:
// $builder->setTimeout($timeout);
// $builder->setCacheDir($cacheDir);

$client = $builder->build();
```

Далее для всей работы с API используются методы объёкта `$client`, который мы получили выше.


## Использование {: #Client }

Работа с SDK сводится к созданию объекта запроса, заполнению его полей согласно документации и требований служб доставки, и передачи этого объекта для запроса. Для простых запросов, вроде получения заказа, SDK реализует методы, которые позволяют делать запросы в упрощённой форме, передавая только необходимый аргумент, такой как ID заказа.

Работа с большинством методов API возможна [только при наличии договора с Яндекс.Доставкой](https://yandex.ru/dev/delivery-3/doc/dg/concepts/access-docpage/).

Перечень основных методов класса `Client` ниже.


| Задача| Метод | Аргумент |
| ----- | -------------- | ----- |
| [Поиск вариантов доставки](#DeliveryOptionsRequest) | `sendDeliveryOptionsRequest` | `DeliveryOptionsRequest` |
| [Поиск пунктов выдачи заказов](#PickupPointsRequest) | `sendPickupPointsRequest` | `PickupPointsRequest` |
| [Создать черновик заказа](#CreateOrderRequest) | `sendCreateOrderRequest` | `CreateOrderRequest` |
| [Обновить черновик заказа](#UpdateOrderRequest) | `sendUpdateOrderRequest` | `UpdateOrderRequest` |
| [Оформить заказ](#) | `sendSubmitOrderRequest` | `SubmitOrderRequest` |
| [Получить данные о заказе](#GetOrderRequest) | `getOrder` | `int $orderId` |
| [Удалить заказ](#DeleteOrderRequest) | `deleteOrder` | `int $orderId` |
| [Получить ярлык заказа](#OrderLabelRequest) | `getLabel` | `int $orderId` |
| [Поиск заказов](#OrdersSearchRequest) | `searchOrders` | `OrdersSearchRequest $request` |
| [Получить историю статусов заказа](#OrderStatusesRequest) | `getOrderStatuses` | `int $orderId` |
| [Получить статус заказов](#OrdersStatusRequest) | `sendOrdersStatusRequest` | `OrdersStatusRequest` |
| [Создать заявку на отгрузку](#) | `...` | `...` |
| [Подтвердить отгрузку](#) | `...` | `...` |
| [Получить список отгрузок](#) | `...` | `...` |
| [Получить интервалы самопривозов](#ImportIntervalsRequest) | `sendImportIntervalsRequest` | `ImportIntervalsRequest` |
| [Получить интервалы заборов](#WithdrawIntervalsRequest) | `sendWithdrawIntervalsRequest` | `WithdrawIntervalsRequest` |
| [Получить акт передачи заказов](#) | `...` | `...` |
| [Получить полный адрес](#LocationRequest) | `getLocations` | `string $term` |
| [Получить индекс по адресу](#PostalCodeRequest) | `getPostalCodes` | `string $address` |
| [Получить список служб доставки](#DeliveryServicesRequest) | `getDeliveryServices` | `int $cabinetId` |

Для всех методов есть по крайней один пример использования в каталоге `examples`.

### Обработка ошибок {: #hasErrors }

Все возвращаемые ответы содержат методы для проверки на ошибку, также для получения списка сообщений включая сообщения об ошибках.

```php
/** @var \CommonSDK\Contracts\Response $response */
$response = $client->sendSomeRequest($request);

if ($response->hasErrors()) {
    // Обрабатываем ошибки
    foreach ($response->getMessages() as $message) {
        if ($message->getErrorCode() !== '') {
            // Это ошибка; бывают случаи когда сообщение с ошибкой - пустая строка.
            // Потому нужно смотреть и на код, и на описание ошибки.
            $message->getErrorCode();
            $message->getMessage();
        }
    }
}
```
В редких случаях при запросе и при работе с объектами могут возникнуть исключения.

Кроме обработки явных ошибок следует обратить внимание на провеку количества ответов в запросах, возвращающих списки:

```php
if (!count($response)) {
    // Ничего не найдено по запросу
}
```

## Варианты доставки

### Поиск вариантов доставки {: #DeliveryOptionsRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\DeliveryOptionsRequest();
$request->senderId = YANDEX_SHOP_ID;

$request->from->location = 'Москва, Красная пл., 1';
$request->from->geoId = 890567;

$request->to->location = 'Новосибирск, Красный пр., 36';
//$request->to->geoId = 4444;
//$request->to->pickupPointIds = [11111, 222222];

$request->dimensions->length = 10;
$request->dimensions->width = 20;
$request->dimensions->height = 30;
$request->dimensions->weight = 5.25;

$request->deliveryType = $request::DELIVERY_TYPE_POST;

$request->shipment->date = new DateTime('next Monday');
$request->shipment->type = $request->shipment::TYPE_IMPORT;
//$request->shipment->partnerId = 1111;
//$request->shipment->warehouseId = 2222;
//$request->shipment->includeNonDefault = true;

$request->cost->assessedValue = 1000;
$request->cost->itemsSum = 1000;
$request->cost->manualDeliveryForCustomer = 750;
$request->cost->fullyPrepaid = true;

// $request->tariffId = 3333;

$response = $client->sendDeliveryOptionsRequest($request);

count($response);

foreach ($response as $value) {
    echo join("\t", [
        $value->tariffId,
        $value->tariffName ?? 'Без названия',
        $value->cost->delivery,
        $value->cost->deliveryForCustomer,
        $value->cost->deliveryForSender,
    ]), "\n";

    echo join("\t", [
        $value->delivery->type,
        $value->delivery->partner->id,
        $value->delivery->partner->name,
        $value->delivery->calculatedDeliveryDateMin->format('Y-m-d'),
        $value->delivery->calculatedDeliveryDateMax->format('Y-m-d'),
    ]), "\n";

    foreach ($value->services as $service) {
        echo "\t- ";
        echo join("\t", [
            $service->name,
            $service->code,
            $service->cost,
            $service->customerPay,
            $service->enabledByDefault,
        ]), "\n";
    }
}
```

### Поиск пунктов выдачи заказов {: #PickupPointsRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\PickupPointsRequest();
$request->type = $request::TYPE_TERMINAL;
$request->locationId = 65;
$request->latitude->from = 55.013;
$request->latitude->to = 55.051;
$request->longitude->from = 82.951;
$request->longitude->to = 83.081;

$response = $client->sendPickupPointsRequest($request);

count($response);

foreach ($response as $item) {
    /** @var \YDeliverySDK\Responses\Types\PickupPoint $item */
    echo join("\t", [
        $item->id,
        $item->partnerId,
        $item->type,
        $item->address->postalCode,
        $item->address->locationId,
        $item->address->latitude,
        $item->address->longitude,
        $item->phones[0]->number,
    ]), "\n";
}
```

## Операции с заказами

### Создать черновик заказа {: #CreateOrderRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\CreateOrderRequest();
$request->deliveryType = $request::DELIVERY_TYPE_COURIER;
$request->comment = 'Пустой тестовый заказ.';
$request->senderId = YANDEX_SHOP_ID;

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

echo "\n\nOrder ID: {$response->id}\n\n";
```

### Обновить черновик заказа {: #UpdateOrderRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\UpdateOrderRequest()
$request->recipient->firstName = 'Василий';
$request->recipient->lastName = 'Юрочкин';

$response = $client->sendUpdateOrderRequest($request);

echo "\n\nOrder ID: {$response->id}\n\n";
```

Более подробный пример обновления заказа смотрите в каталоге с примерами в файле `130_UpdateOrder.php`.


### Оформить заказ {: #SubmitOrderRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\SubmitOrderRequest([
    $orderId
]);

$response = $client->sendSubmitOrderRequest($request);

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

foreach ($response as $submittedOrder) {
    \var_dump($submittedOrder->orderId);
}

```
Более подробный пример оформления заказа смотрите в каталоге с примерами в файле `110_CreateAndSubmitOrder.php`.

### Получить данные о заказе {: #GetOrderRequest }

```php
$order = $client->getOrder($orderId);
```

### Удалить заказ {: #DeleteOrderRequest }

Если ошибок нет, то значит удаление прошло успешно.

```php
$response = $client->deleteOrder($order->id);

if ($response->hasErrors()) {
   // ...
}
```

### Получить ярлык заказа {: #OrderLabelRequest }

```php
$response = $client->getLabel($order->id);

if ($response->hasErrors()) {
    // Обрабатываем ошибки
    
    return;
}

file_put_contents("label.pdf", $response->getBody());
```

### Поиск заказов {: #OrdersSearchRequest }

Метод поиска заказов возвращает итератор, который выгружает все заказы на всех страницах.

```
use YDeliverySDK\Requests;

$request = new Requests\OrdersSearchRequest([
    YANDEX_SHOP_ID
]);

$request->term = 'Новосибирск';
$request->statuses[] = $request->statuses::CANCELLED;

$orders = $client->searchOrders($request);

foreach ($orders as $order) {
    echo "Page {$orders->pageNumber}\t{$order->id}\t{$order->status}\t{$order->comment}\n";
```

Есть аналогичный метод для загрузки только одной какой-то страницы.

```php
$request->size = 10;
$request->page = 2;

$orders = $client->sendOrdersSearchRequest($request);
```

### Получить историю статусов заказа {: #OrderStatusesRequest }

```php
$statuses = $client->getOrderStatuses($order->id);

echo "Order ID: {$statuses->id}\n";

foreach ($statuses as $status) {
    echo join("\t", [
        $statuses->id,
        $status->code,
        $status->description,
        $status->datetime->format('r'),
    ]), "\n";
}
```

### Получить статус заказов {: #OrdersStatusRequest }

```php
$request = new Requests\OrdersStatusRequest(YANDEX_SHOP_ID);
$request->fromOrderId = $fromOrderId;

$order = $request->addOrder();
$order->id = $orderId;

$order = $request->addOrder();
$order->externalId = $orderNumber;

$response = $client->sendOrdersStatusRequest($request);

foreach ($response as $order) {
    echo join("\t", [
        $order->id ?? $order->externalId,
        $order->status->code,
        $order->status->description,
        $order->status->timestamp->format('r'),
    ]), "\n";
}
```

## Операции с отгрузками

### Создать заявку на отгрузку

Этот метод пока не реализован. 

### Подтвердить отгрузку

Этот метод пока не реализован.

### Получить список отгрузок

Этот метод пока не реализован.

### Получить интервалы самопривозов {: #ImportIntervalsRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\ImportIntervalsRequest();
$request->date = new DateTime('next Monday');

// Используя первый попавшийся склад.
foreach ($partner->warehouses as $warehouse) {
    $request->warehouseId = $warehouse->id;
}

$response = $client->sendImportIntervalsRequest($request);

count($response);

foreach ($response as $value) {
    echo "{$value->id}\t{$value->from}\t{$value->to}\n";
}
```

### Получить интервалы заборов {: #WithdrawIntervalsRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\WithdrawIntervalsRequest();
$request->date = new DateTime('next Monday');
$request->partnerId = $partner->id;

$response = $client->sendWithdrawIntervalsRequest($request);

count($response);

foreach ($response as $value) {
    echo "{$value->id}\t{$value->from}\t{$value->to}\n";
}
```

### Получить акт передачи заказов

Этот метод пока не реализован.

## Справочные данные

### Получить полный адрес {: #LocationRequest }

```php
$response = $client->getLocations($argv[1]);

count($response);

foreach ($response as $value) {
    echo "{$value->geoId}\t{$value->address}\n";

    foreach ($value->addressComponents as $component) {
        echo "- {$component->kind}: {$component->name}\n";
    }
}
```

### Получить индекс по адресу {: #PostalCodeRequest }

```php
$response = $client->getPostalCodes('Москва, ул. Льва Толстого, 16');

count($response);

foreach ($response as $value) {
    echo "{$value}\n";
}
```

### Получить список служб доставки {: #DeliveryServicesRequest }

```php
$response = $client->getDeliveryServices(YANDEX_CABINET_ID);

count($response);

foreach ($response as $value) {
    echo "{$value->id}\t{$value->code}\t{$value->name}\n";

    foreach ($value->warehouses as $warehouse) {
        echo "- {$warehouse->id}\t{$warehouse->name}\t{$warehouse->address}\n";
    }
}
```

## Сервис-провайдер для Laravel 5.1+ {: #Laravel}

```php
// config/app.php

    'providers' => [
        // ...

        \YDeliverySDK\LaravelServiceProvider::class

        // ...
    ]
```

```php
// config/services.php

    'ydelivery' => [
        'token'  => env('YANDEX_DELIVERY_ACCOUNT', ''),
    ],
```

## Отладка получаемых ответов {: #DebuggingLogger }

Посмотреть, что конкретно отвечает Яндекс.Доставка на наши запросы и какие запросы мы посылаем сами можно используя [стандартный PSR-3 логгер](https://github.com/php-fig/fig-standards/blob/main/accepted/PSR-3-logger-interface.md), такой, как, например, [Monolog](https://github.com/Seldaek/monolog).

```php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$monolog = new Logger('ydostavka');
$monolog->pushHandler(new StreamHandler('ydostavka.log', Logger::DEBUG));

$builder->setLogger($monolog);
```

Текстовые запросы и ответы в исходном виде идут с уровнем `DEBUG`.

## Замечания {: #contribute}

- [Инструкции по доработке и тестированию.](https://github.com/sanmai/ydelivery-sdk/blob/main/CONTRIBUTING.md)

- [Общие инструкции по работе с GitHub.](https://www.alexeykopytko.com/2018/github-contributor-guide/) Если это ваш первый PR, очень рекомендуем ознакомиться.

### О форматах даты и времени {: #DateTimeImmutable }

Для указания даты и времени в запросах везде можно использовать ровно как `DateTime`, так и `DateTimeImmutable`. Если указано, что параметр принимает строку, то можно и строку: в том формате, который подразумевает API.

## Лицензия {: #license }

Данный SDK распространяется под лицензией MIT.

This project is licensed under the terms of the MIT license.
