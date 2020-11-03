# PHP SDK для API Яндекс.Доставки

[![Latest Stable Version](https://poser.pugx.org/sanmai/ydelivery-sdk/v/stable)](https://packagist.org/packages/sanmai/ydelivery-sdk)
[![Build Status](https://travis-ci.com/sanmai/ydelivery-sdk.svg?branch=main)](https://travis-ci.com/sanmai/ydelivery-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/ydelivery-sdk/badge.svg?branch=main)](https://coveralls.io/github/sanmai/ydelivery-sdk?branch=main)
[![Documentation Status](https://readthedocs.org/projects/ydelivery-sdk/badge/?version=latest)](https://ydelivery-sdk.readthedocs.io/?badge=latest)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpydeliverysdk)

Перед вами SDK для [интеграции с обновлённой Яндекс.Доставкой](https://yandex.ru/support/delivery-3/register.html).

Возможности:

- [x] Варианты доставки
   - [x] Поиск вариантов доставки
   - [x] Поиск пунктов выдачи заказов
- [x] Операции с заказами
   - [x] Создать черновик заказа
   - [x] Обновить черновик заказа
   - [x] Оформить заказ
   - [x] Получить данные о заказе
   - [x] Удалить заказ
   - [x] Получить ярлык заказа
   - [x] Поиск заказов
   - [x] Получить историю статусов заказа
   - [x] Получить статус заказов
- [ ] Операции с отгрузками
  - [ ] Создать заявку на отгрузку
  - [ ] Подтвердить отгрузку
  - [ ] Получить список отгрузок
  - [x] Получить интервалы самопривозов
  - [x] Получить интервалы заборов
  - [ ] Получить акт передачи заказов
- [x] Справочные данные
  - [x] Получить полный адрес
  - [x] Получить индекс по адресу
  - [x] Получить список служб доставки
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




