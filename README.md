# PHP SDK для API Яндекс.Доставки

[![Latest Stable Version](https://poser.pugx.org/sanmai/ydelivery-sdk/v/stable)](https://packagist.org/packages/sanmai/ydelivery-sdk)
[![Build Status](https://travis-ci.com/sanmai/ydelivery-sdk.svg?branch=master)](https://travis-ci.com/sanmai/ydelivery-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/ydelivery-sdk/badge.svg?branch=master)](https://coveralls.io/github/sanmai/ydelivery-sdk?branch=master)
[![Documentation Status](https://readthedocs.org/projects/ydelivery-sdk/badge/?version=latest)](https://ydelivery-sdk.readthedocs.io/?badge=latest)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpydeliverysdk)

Перед вами SDK для [интеграции с обновлённой Яндекс.Доставкой](https://yandex.ru/support/delivery-3/register.html).

Возможности:

- [ ] Варианты доставки
   - [x] Поиск вариантов доставки
   - [ ] Поиск пунктов выдачи заказов
- [ ] Операции с заказами
   - [ ] Создать черновик заказа
   - [ ] Обновить черновик заказа
   - [ ] Оформить заказ
   - [ ] Получить данные о заказе
   - [ ] Удалить заказ
   - [ ] Получить ярлык заказа
   - [ ] Поиск заказов
   - [ ] Получить историю статусов заказа
   - [ ] Получить статус заказов
- [ ] Операции с отгрузками
  - [ ] Создать заявку на отгрузку
  - [ ] Подтвердить отгрузку
  - [ ] Получить список отгрузок
  - [x] Получить интервалы самопривозов
  - [x] Получить интервалы заборов
  - [ ] Получить акт передачи заказов
- [ ] Справочные данные
  - [x] Получить полный адрес
  - [x] Получить индекс по адресу
  - [x] Получить список служб доставки
- Чего-то нет в списке? [Напишите, сообщите.](https://github.com/sanmai/delivery-sdk/issues/new/choose)

Работа с большинством методов API возможна [только при наличии договора с Яндекс.Доставкой](https://yandex.ru/dev/delivery-3/doc/dg/concepts/access-docpage/).


## Установка

```bash
composer require sanmai/ydelivery-sdk
```
Для работы SDK нужен PHP 7.3 или выше. Работа протестирована под PHP 7.3, 7.4.

[Полная документация по всем методам.](https://ydelivery-sdk.readthedocs.io/)

## Лицензия

Данный SDK распространяется [под лицензией MIT](LICENSE).

This project is licensed under the terms of the MIT license.




