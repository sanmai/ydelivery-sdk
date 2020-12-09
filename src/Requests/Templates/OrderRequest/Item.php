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

namespace YDeliverySDK\Requests\Templates\OrderRequest;

use CommonSDK\Concerns\ObjectPropertyRead;
use CommonSDK\Concerns\PropertyWrite;
use YDeliverySDK\Common;
use YDeliverySDK\Requests\Types\Dimensions;

/**
 * @property-write string $externalId Идентификатор заказа в системе партнера.
 * @property-write string $name Название товара.
 * @property-write int $count Количество единиц товара.
 * @property-write float $price Цена товара в рублях.
 * @property-write int $assessedValue Объявленная стоимость заказа.
 * @property-write string $tax Вид налогообложения товара: VAT_20 — НДС 20%, VAT_10 — НДС 10%, VAT_0 — НДС 0%, NO_VAT — не облагается НДС.
 * @property-read  Dimensions $dimensions Вес и габариты отправления.
 */
final class Item extends Common\Item
{
    use ObjectPropertyRead;
    use PropertyWrite;

    /**
     * @phan-suppress PhanAccessReadOnlyMagicProperty
     */
    public function __construct(?Dimensions $dimensions = null)
    {
        /** @phpstan-ignore-next-line */
        $this->dimensions = $dimensions ?? new Dimensions();
    }
}
