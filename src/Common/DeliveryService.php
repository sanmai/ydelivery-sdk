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
 * @property-read string $code
 * @property-read float $cost
 * @property-read boolean $customerPay Услуга оплачивается клиентом.
 *
 * @property-write string $code
 * @property-write float $cost
 * @property-write boolean $customerPay Услуга оплачивается клиентом.
 *
 */

/**
 * Основа для объектов, используемых в запросах и ответах.
 */
abstract class DeliveryService
{
    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $name;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $code;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $cost;

    /**
     * @JMS\Type("boolean")
     *
     * @var bool
     */
    protected $customerPay;

    /**
     * @JMS\Type("boolean")
     *
     * @var bool
     */
    protected $enabledByDefault;
}
