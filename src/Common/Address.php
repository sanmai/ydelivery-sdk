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
 * @property-read int $geoId
 * @property-read string $country
 * @property-read string $region
 * @property-read string $locality
 * @property-read string $street
 * @property-read string $house
 * @property-read string $housing
 * @property-read string $building
 * @property-read string $apartment
 * @property-read string $postalCode
 * @property-read string $postCode
 *
 * @property-write int $geoId
 * @property-write string $country
 * @property-write string $region
 * @property-write string $locality
 * @property-write string $street
 * @property-write string $house
 * @property-write string $housing
 * @property-write string $building
 * @property-write string $apartment
 * @property-write string $postalCode
 * @property-write string $postCode
 */

/**
 * Основа для объектов адреса, используемых в запросах и ответах.
 */
abstract class Address
{
    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $geoId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $country;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $region;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $locality;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $street;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $house;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $housing;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $building;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $apartment;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $postalCode;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $postCode;
}
