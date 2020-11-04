# PHP SDK для API Яндекс.Доставки

[![Latest Stable Version](https://poser.pugx.org/sanmai/ydelivery-sdk/v/stable)](https://packagist.org/packages/sanmai/ydelivery-sdk)
[![Build Status](https://travis-ci.com/sanmai/ydelivery-sdk.svg?branch=main)](https://travis-ci.com/sanmai/ydelivery-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/ydelivery-sdk/badge.svg?branch=main)](https://coveralls.io/github/sanmai/ydelivery-sdk?branch=main)
[![Documentation Status](https://readthedocs.org/projects/ydelivery-sdk/badge/?version=latest)](https://ydelivery-sdk.readthedocs.io/?badge=latest)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpydeliverysdk)

Перед вами SDK для [интеграции с обновлённой Яндекс.Доставкой](https://yandex.ru/support/delivery-3/register.html).

Возможности:

- [x] Варианты доставки
   - [x] [Поиск вариантов доставки](https://ydelivery-sdk.readthedocs.io/ru/latest/#DeliveryOptionsRequest)
   - [x] [Поиск пунктов выдачи заказов](https://ydelivery-sdk.readthedocs.io/ru/latest/#PickupPointsRequest)
- [x] Операции с заказами
   - [x] [Создать черновик заказа](https://ydelivery-sdk.readthedocs.io/ru/latest/#CreateOrderRequest)
   - [x] [Обновить черновик заказа](https://ydelivery-sdk.readthedocs.io/ru/latest/#UpdateOrderRequest)
   - [x] [Оформить заказ](https://ydelivery-sdk.readthedocs.io/ru/latest/#)
   - [x] [Получить данные о заказе](https://ydelivery-sdk.readthedocs.io/ru/latest/#GetOrderRequest)
   - [x] [Удалить заказ](https://ydelivery-sdk.readthedocs.io/ru/latest/#DeleteOrderRequest)
   - [x] [Получить ярлык заказа](https://ydelivery-sdk.readthedocs.io/ru/latest/#OrderLabelRequest)
   - [x] [Поиск заказов](https://ydelivery-sdk.readthedocs.io/ru/latest/#OrdersSearchRequest)
   - [x] [Получить историю статусов заказа](https://ydelivery-sdk.readthedocs.io/ru/latest/#OrderStatusesRequest)
   - [x] [Получить статус заказов](https://ydelivery-sdk.readthedocs.io/ru/latest/#OrdersStatusRequest)
- [ ] Операции с отгрузками
  - [ ] Создать заявку на отгрузку
  - [ ] Подтвердить отгрузку
  - [ ] Получить список отгрузок
  - [x] [Получить интервалы самопривозов](https://ydelivery-sdk.readthedocs.io/ru/latest/#ImportIntervalsRequest)
  - [x] [Получить интервалы заборов](https://ydelivery-sdk.readthedocs.io/ru/latest/#WithdrawIntervalsRequest)
  - [ ] Получить акт передачи заказов
- [x] Справочные данные
  - [x] [Получить полный адрес](https://ydelivery-sdk.readthedocs.io/ru/latest/#LocationRequest)
  - [x] [Получить индекс по адресу](https://ydelivery-sdk.readthedocs.io/ru/latest/#PostalCodeRequest)
  - [x] [Получить список служб доставки](https://ydelivery-sdk.readthedocs.io/ru/latest/#DeliveryServicesRequest)
- Чего-то нет в списке? [Напишите, сообщите.](https://github.com/sanmai/delivery-sdk/issues/new/choose)

Работа с большинством методов API возможна [только при наличии договора с Яндекс.Доставкой](https://yandex.ru/dev/delivery-3/doc/dg/concepts/access-docpage/).

## Установка

```bash
composer require sanmai/ydelivery-sdk
```
Для работы SDK нужен PHP 7.3 или выше. Работа протестирована под PHP 7.3, 7.4, 8.0, 8.1.

[Полная документация по всем методам.](https://ydelivery-sdk.readthedocs.io/)

## Лицензия

Данный SDK распространяется [под лицензией MIT](LICENSE).

This project is licensed under the terms of the MIT license.




