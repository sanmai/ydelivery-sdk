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

namespace YDeliverySDK\Responses\Types\DeliveryOption;

use CommonSDK\Concerns\PropertyRead;
use DateTimeImmutable;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Common\Concerns\NamedEntity;

/**
 * @property-read DateTimeImmutable $date Дата отгрузки.
 * @property-read string $type Вид отгрузки. IMPORT — самопривоз, WITHDRAW — забор.
 * @property-read Partner $partner Принимающий партнер.
 * @property-read NamedEntity|null $warehouse Принимающий склад.
 * @property-read bool $settingsDefault Отгрузка соответствует настройкам магазина по умолчанию.
 * @property-read bool|null $alreadyExists Отгрузка уже существует. Заполняется при указанном идентификаторе склада отправления.
 */
final class Shipment
{
    use PropertyRead;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $type;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\DeliveryOption\Partner")
     *
     * @var Partner
     */
    private $partner;

    /**
     * @JMS\Type("YDeliverySDK\Common\NamedEntity")
     *
     * @var NamedEntity|null
     */
    private $warehouse;

    /**
     * @JMS\Type("bool")
     *
     * @var bool
     */
    private $settingsDefault;

    /**
     * @JMS\Type("bool")
     *
     * @var bool|null
     */
    private $alreadyExists;
}
