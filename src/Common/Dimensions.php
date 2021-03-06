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
 * @property-read float $length Длина в сантиметрах.
 * @property-read float $width Высота в сантиметрах.
 * @property-read float $height Ширина в сантиметрах.
 * @property-read float $weight Вес брутто в килограммах.
 *
 * @property-write float $length Длина в сантиметрах.
 * @property-write float $height Высота в сантиметрах.
 * @property-write float $width Ширина в сантиметрах.
 * @property-write float $weight Вес брутто в килограммах.
 */

/**
 * Основа для объектов, используемых в запросах и ответах.
 */
abstract class Dimensions
{
    /**
     * Длина в сантиметрах.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $length;

    /**
     * Высота в сантиметрах.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $height;

    /**
     * Ширина в сантиметрах.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $width;

    /**
     * Вес брутто в килограммах.
     *
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $weight;
}
