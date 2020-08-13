

Перед вами SDK для [интеграции с обновлённой Яндекс.Доставкой](https://yandex.ru/support/delivery-3/register.html) с открытым [исходным кодом](https://github.com/sanmai/ydelivery-sdk) согласно [документации на API](https://yandex.ru/support/delivery-3/api.html).

## Установка  {: #install }

```bash
composer require sanmai/ydelivery-sdk
```
Для работы SDK нужен PHP 7.3 или выше. Работа протестирована под PHP 7.3, 7.4.

### Гарантии обратной совместимости {: #backward-compatibility }

При разработке этой библиотеки большое внимание уделяется обратной совместимости API в пределах основной версии. Если вы установили когда-то версию ветки 0.1, например 0.1.7, то после обновления до 0.1.8 или даже до 0.1.12 вы можете рассчитывать что весь ваш код будет работать точно так же как раньше, без необходимости что-то менять, при условии, конечно, что API самой Яндекс.Доставки не поменялось. Такого же принципа работы с версиями [по умолчанию придерживается Composer](https://getcomposer.org/doc/articles/versions.md#caret-version-range-).

Гарантии обратной совместимости в части возвращаемых типов распостраняются только на имплементируемые ими интерфейсы. Если вы получали объект имплементирующий `Psr\Http\Message\ResponseInterface`, то такой же объёкт вы продолжите получать. Если у возвращенного объёкта был какой-то метод, то такой же метод будет у объекта в следующей неосновной версии. Конкретный тип может быть другим, рассчитывать на это не нужно, проверять принадлежность конкретному типу также не следует. [Как проверять ответы на ошибки.](#hasErrors)

Такие строгие гарантии обратной совместимости API были бы невозможны без идущей рука об руку с ними минимизации точек для расширения API: наследование для большинства классов не только не предусмотрено, но и просто невозможно. Впрочем, для удобства композиции есть необходимые интерфейсы. Мы исходим из того что добавить ещё интерфейсы проблемы не представляет, новые интерфейсы не ломают обратную совместимость.

После выхода версии 1.0 обратная совместимость будет поддерживаться в пределах мажорной версии.

## Инициализация {: #initialize }

```php
require_once 'vendor/autoload.php';

$builder = new \YDeliverySDK\ClientBuilder();
$builder->setToken($token);

$client = $builder->build();

```

Далее для всей работы с API используются методы объёкта `$client`, который мы получили выше.


## Использование {: #methods }

Перечень основных методов класса `Client` ниже.

| Задача| Метод | Аргумент |
| ----- | -------------- | ----- |
| [Получить индекс по адресу](#PostalCodeRequest) | `sendPostalCodeRequest` | `PostalCodeRequest` |


### Обработка ошибок {: #hasErrors }

Все возвращаемые ответы содержат методы для проверки на ошибку, также для получения списка сообщений включая сообщения об ошибках.

```php
/** @var \YDeliverySDK\Contracts\Response $response */
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

### Получить индекс по адресу {: #PostalCodeRequest }

```php
use YDeliverySDK\Requests;

$request = new Requests\PostalCodeRequest();
$request->setAddress('Москва, ул. Льва Толстого, 16');

$response = $client->sendPostalCodeRequest($request);

foreach ($response as $value) {
    echo "{$value}\n";
}
```

### Сервис-провайдер для Laravel 5.1+ {: #Laravel}

```php
// config/app.php

    'providers' => [
        // ...

        \YDeliverySDK\LaravelServiceProvider::class

        // ...
    ]

// config/services.php

    'ydelivery' => [
        'token'  => env('YANDEX_DELIVERY_ACCOUNT', ''),
    ],
```

### Отладка получаемых ответов {: #DebuggingLogger }

Посмотреть, что конкретно отвечает СДЭК на наши запросы и какие запросы мы посылаем сами можно используя [стандартный PSR-3 логгер](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md), такой, как, например, [Monolog](https://github.com/Seldaek/monolog).

```php
$builder->setLogger($monolog);
```

Текстовые запросы и ответы в исходном виде идут с уровнем `DEBUG`.

## Замечания {: #contribute}

- [Инструкции по доработке и тестированию.](https://github.com/sanmai/ydelivery-sdk/blob/master/CONTRIBUTING.md)

- [Общие инструкции по работе с GitHub.](https://www.alexeykopytko.com/2018/github-contributor-guide/) Если это ваш первый PR, очень рекомендуем ознакомиться.

### О форматах даты и времени {: #DateTimeImmutable }

Для указания даты и времени в запросах везде можно использовать ровно как `DateTime`, так и `DateTimeImmutable`.

## Лицензия {: #license }

Данный SDK распространяется под лицензией MIT.

This project is licensed under the terms of the MIT license.
