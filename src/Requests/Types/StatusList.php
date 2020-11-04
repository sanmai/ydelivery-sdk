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

namespace YDeliverySDK\Requests\Types;

use CommonSDK\Contracts\ReadableRequestProperty;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template-impelements array<string>
 */
final class StatusList extends ArrayCollection implements ReadableRequestProperty
{
    /**
     * Заказ доступен для редактирования.
     */
    public const DRAFT = 'DRAFT';

    /**
     * Заказ находится на проверке.
     */
    public const VALIDATING = 'VALIDATING';

    /**
     * Заказ не прошел проверку. Редактирование заказа переведет его в статус DRAFT.
     */
    public const VALIDATING_ERROR = 'VALIDATING_ERROR';

    /**
     * Заказ проверен и создан в системе Яндекс.Доставки.
     */
    public const CREATED = 'CREATED';

    /**
     * Отправлен запрос на создание заказа в системе службы доставки.
     */
    public const DELIVERY_PROCESSING_STARTED = 'DELIVERY_PROCESSING_STARTED';

    /**
     * Заказ создан в системе службы доставки.
     */
    public const DELIVERY_TRACK_RECEIVED = 'DELIVERY_TRACK_RECEIVED';

    /**
     * Заказ ожидает подтверждения от службы доставки.
     */
    public const SENDER_SENT = 'SENDER_SENT';

    /**
     * Заказ подтвержден службой доставки.
     */
    public const DELIVERY_LOADED = 'DELIVERY_LOADED';

    /**
     * Заказ ожидается на едином складе.
     */
    public const SENDER_WAIT_FULFILLMENT = 'SENDER_WAIT_FULFILLMENT';

    /**
     * Заказ ожидается в службе доставки.
     */
    public const SENDER_WAIT_DELIVERY = 'SENDER_WAIT_DELIVERY';

    /**
     * Заказ находится на складе службы доставки.
     */
    public const DELIVERY_AT_START = 'DELIVERY_AT_START';

    /**
     * Заказ находится на сортировке в службе доставки.
     */
    public const DELIVERY_AT_START_SORT = 'DELIVERY_AT_START_SORT';

    /**
     * Заказ доставляется.
     */
    public const DELIVERY_TRANSPORTATION = 'DELIVERY_TRANSPORTATION';

    /**
     * Заказ находится в населенном пункте получателя.
     */
    public const DELIVERY_ARRIVED = 'DELIVERY_ARRIVED';

    /**
     * Заказ доставляется по населенному пункту получателя.
     */
    public const DELIVERY_TRANSPORTATION_RECIPIENT = 'DELIVERY_TRANSPORTATION_RECIPIENT';

    /**
     * Заказ проходит таможенный контроль.
     */
    public const DELIVERY_CUSTOMS_ARRIVED = 'DELIVERY_CUSTOMS_ARRIVED';

    /**
     * Заказ передан в доставку по России.
     */
    public const DELIVERY_CUSTOMS_CLEARED = 'DELIVERY_CUSTOMS_CLEARED';

    /**
     * Срок хранения заказа в службе доставки увеличен.
     */
    public const DELIVERY_STORAGE_PERIOD_EXTENDED = 'DELIVERY_STORAGE_PERIOD_EXTENDED';

    /**
     * Срок хранения заказа в службе доставки истек.
     */
    public const DELIVERY_STORAGE_PERIOD_EXPIRED = 'DELIVERY_STORAGE_PERIOD_EXPIRED';

    /**
     * Доставка перенесена по вине магазина.
     */
    public const DELIVERY_UPDATED_BY_SHOP = 'DELIVERY_UPDATED_BY_SHOP';

    /**
     * Доставка перенесена по просьбе клиента.
     */
    public const DELIVERY_UPDATED_BY_RECIPIENT = 'DELIVERY_UPDATED_BY_RECIPIENT';

    /**
     * Доставка перенесена службой доставки.
     */
    public const DELIVERY_UPDATED_BY_DELIVERY = 'DELIVERY_UPDATED_BY_DELIVERY';

    /**
     * Заказ находится в пункте самовывоза.
     */
    public const DELIVERY_ARRIVED_PICKUP_POINT = 'DELIVERY_ARRIVED_PICKUP_POINT';

    /**
     * Заказ вручен клиенту.
     */
    public const DELIVERY_TRANSMITTED_TO_RECIPIENT = 'DELIVERY_TRANSMITTED_TO_RECIPIENT';

    /**
     * Заказ доставлен получателю.
     */
    public const DELIVERY_DELIVERED = 'DELIVERY_DELIVERED';

    /**
     * Неудачная попытка вручения заказа.
     */
    public const DELIVERY_ATTEMPT_FAILED = 'DELIVERY_ATTEMPT_FAILED';

    /**
     * Заказ не может быть доставлен.
     */
    public const DELIVERY_CAN_NOT_BE_COMPLETED = 'DELIVERY_CAN_NOT_BE_COMPLETED';

    /**
     * Заказ готовится к возврату.
     */
    public const RETURN_PREPARING = 'RETURN_PREPARING';

    /**
     * Заказ возвращен на склад службы доставки.
     */
    public const RETURN_ARRIVED_DELIVERY = 'RETURN_ARRIVED_DELIVERY';

    /**
     * Заказ передан на единый склад.
     */
    public const RETURN_TRANSMITTED_FULFILLMENT = 'RETURN_TRANSMITTED_FULFILLMENT';

    /**
     * Заказ ожидает подтверждения от сортировочного центра.
     */
    public const SORTING_CENTER_CREATED = 'SORTING_CENTER_CREATED';

    /**
     * Отправлен запрос на создание заказа в системе сортировочного центра.
     */
    public const SORTING_CENTER_PROCESSING_STARTED = 'SORTING_CENTER_PROCESSING_STARTED';

    /**
     * Заказ создан в системе сортировочного центра.
     */
    public const SORTING_CENTER_TRACK_RECEIVED = 'SORTING_CENTER_TRACK_RECEIVED';

    /**
     * Заказ подтвержден сортировочным центром.
     */
    public const SORTING_CENTER_LOADED = 'SORTING_CENTER_LOADED';

    /**
     * Заказ поступил на склад сортировочного центра.
     */
    public const SORTING_CENTER_AT_START = 'SORTING_CENTER_AT_START';

    /**
     * Некоторые позиции заказа отсутствуют на складе сортировочного центра.
     */
    public const SORTING_CENTER_OUT_OF_STOCK = 'SORTING_CENTER_OUT_OF_STOCK';

    /**
     * Ожидаются уточнения по заказу в сортировочном центре.
     */
    public const SORTING_CENTER_AWAITING_CLARIFICATION = 'SORTING_CENTER_AWAITING_CLARIFICATION';

    /**
     * Заказ на складе сортировочного центра подготовлен к отправке в службу доставки.
     */
    public const SORTING_CENTER_PREPARED = 'SORTING_CENTER_PREPARED';

    /**
     * Заказ отгружен сортировочным центром в службу доставки.
     */
    public const SORTING_CENTER_TRANSMITTED = 'SORTING_CENTER_TRANSMITTED';

    /**
     * Сортировочный центр получил данные о планируемом возврате заказа.
     */
    public const SORTING_CENTER_RETURN_PREPARING = 'SORTING_CENTER_RETURN_PREPARING';

    /**
     * Возвратный заказ готов к передаче в сортировочный центр.
     */
    public const SORTING_CENTER_RETURN_RFF_PREPARING_FULFILLMENT = 'SORTING_CENTER_RETURN_RFF_PREPARING_FULFILLMENT';

    /**
     * Возвратный заказ передан в сортировочный центр.
     */
    public const SORTING_CENTER_RETURN_RFF_TRANSMITTED_FULFILLMENT = 'SORTING_CENTER_RETURN_RFF_TRANSMITTED_FULFILLMENT';

    /**
     * Возвратный заказ поступил на склад сортировочного центра.
     */
    public const SORTING_CENTER_RETURN_RFF_ARRIVED_FULFILLMENT = 'SORTING_CENTER_RETURN_RFF_ARRIVED_FULFILLMENT';

    /**
     * Возвратный заказ на складе сортировочного центра.
     */
    public const SORTING_CENTER_RETURN_ARRIVED = 'SORTING_CENTER_RETURN_ARRIVED';

    /**
     * Возвратный заказ готов для передачи магазину.
     */
    public const SORTING_CENTER_RETURN_PREPARING_SENDER = 'SORTING_CENTER_RETURN_PREPARING_SENDER';

    /**
     * Возвратный заказ передан на доставку в магазин.
     */
    public const SORTING_CENTER_RETURN_TRANSFERRED = 'SORTING_CENTER_RETURN_TRANSFERRED';

    /**
     * Заказ возвращен в магазин.
     */
    public const SORTING_CENTER_RETURN_RETURNED = 'SORTING_CENTER_RETURN_RETURNED';

    /**
     * Заказ отменен.
     */
    public const SORTING_CENTER_CANCELED = 'SORTING_CENTER_CANCELED';

    /**
     * Ошибка создания заказа в сортировочном центре.
     */
    public const SORTING_CENTER_ERROR = 'SORTING_CENTER_ERROR';

    /**
     * Заказ утерян в процессе доставки.
     */
    public const LOST = 'LOST';

    /**
     * Статус заказа уточняется.
     */
    public const UNEXPECTED = 'UNEXPECTED';

    /**
     * Заказ отменен.
     */
    public const CANCELLED = 'CANCELLED';

    /**
     * Заказ отменен покупателем.
     */
    public const CANCELLED_USER = 'CANCELLED_USER';

    /**
     * Финальный статус заказа.
     */
    public const FINISHED = 'FINISHED';

    /**
     * Ошибка создания заказа.
     */
    public const ERROR = 'ERROR';

    /**
     * Ошибка обработки заказа.
     */
    public const GENERIC_ERROR = 'GENERIC_ERROR';

    /**
     * Заказ не найден.
     */
    public const NOT_FOUND = 'NOT_FOUND';
}
