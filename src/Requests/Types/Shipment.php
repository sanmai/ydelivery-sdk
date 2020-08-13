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

use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Requests\Concerns\MagicSetters;

/**
 * @method Shipment setType(string $value)
 * @method Shipment setPartnerId(int $value)
 * @method Shipment setWarehouseId(int $value)
 * @method Shipment setIncludeNonDefault(bool $value)
 */
final class Shipment
{
    use MagicSetters;

    /**
     * Вид отгрузки: самопривоз.
     *
     * @var string
     */
    public const TYPE_IMPORT = 'IMPORT';

    /**
     * Вид отгрузки: забор.
     *
     * @var string
     */
    public const TYPE_WITHDRAW = 'WITHDRAW';

    /**
     * Дата отгрузки в формате YYYY-MM-DD.
     *
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * Вид отгрузки.
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    private $type;

    /**
     * Параметр учитывается только при запросе всех вариантов доставки (includeNonDefault = true).
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    private $partnerId;

    /**
     * Идентификатор склада.
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    private $warehouseId;

    /**
     * Нужно ли включить дополнительные варианты доставки в запрос, кроме тех, что заявлены в ЛК.
     *
     * @JMS\Type("bool")
     *
     * @var bool
     */
    private $includeNonDefault;

    public function setDate(\DateTimeInterface $date)
    {
        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        $this->date = $date;

        return $this;
    }
}
