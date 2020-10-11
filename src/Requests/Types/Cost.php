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

use CommonSDK\Concerns\MagicSetters;
use JMS\Serializer\Annotation as JMS;

/**
 * @method Cost setAssessedValue(float $value)
 * @method Cost setItemsSum(float $value)
 * @method Cost setManualDeliveryForCustomer(float $value)
 * @method Cost setFullyPrepaid(bool $value)
 */
final class Cost
{
    use MagicSetters;

    /**
     * Объявленная стоимость заказа. Влияет на стоимость страховки.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    private $assessedValue;

    /**
     * Суммарная стоимость товаров заказа. Влияет на стоимость наложенного платежа.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    private $itemsSum;

    /**
     * Стоимость доставки для покупателя (определяется магазином).
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    private $manualDeliveryForCustomer;

    /**
     * Заказ полностью предоплачен.
     *
     * @JMS\Type("bool")
     *
     * @var bool
     */
    private $fullyPrepaid;
}
