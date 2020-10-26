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

namespace YDeliverySDK\Common;

use JMS\Serializer\Annotation as JMS;

/*
 *
 * @property-read string $externalId Идентификатор заказа в системе партнера.
 * @property-read string $name Название товара.
 * @property-read int $count  Количество единиц товара.
 * @property-read double $price Цена товара в рублях.
 * @property-read int $assessedValue Объявленная стоимость заказа.
 * @property-read string $tax Вид налогообложения товара: VAT_20 — НДС 20%, VAT_10 — НДС 10%, VAT_0 — НДС 0%, NO_VAT — не облагается НДС.
 *
 * @property-write string $externalId Идентификатор заказа в системе партнера.
 * @property-write string $name Название товара.
 * @property-write int $count Количество единиц товара.
 * @property-write double $price Цена товара в рублях.
 * @property-write int $assessedValue Объявленная стоимость заказа.
 * @property-write string $tax Вид налогообложения товара: VAT_20 — НДС 20%, VAT_10 — НДС 10%, VAT_0 — НДС 0%, NO_VAT — не облагается НДС.
 *
 */

/**
 * Основа для объектов, используемых в запросах и ответах.
 */
abstract class Item
{
    public const TAX_NO_VAT = 'NO_VAT';
    public const TAX_VAT_20 = 'VAT_20';
    public const TAX_VAT_10 = 'VAT_10';
    public const TAX_VAT_0 = 'VAT_0';

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $externalId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $name;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $count;

    /**
     * @JMS\Type("double")
     *
     * @var float
     */
    protected $price;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $assessedValue;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $tax;

    /**
     * @JMS\Type("YDeliverySDK\Common\Dimensions")
     * @JMS\SkipWhenEmpty
     *
     * @var Dimensions
     */
    protected $dimensions;
}
