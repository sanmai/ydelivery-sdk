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

namespace YDeliverySDK\Responses\Types\PickupPoint;

use CommonSDK\Concerns\PropertyRead;
use JMS\Serializer\Annotation as JMS;

/**
 * @property-read int $locationId
 * @property-read float $latitude
 * @property-read float $longitude
 * @property-read string $postalCode
 * @property-read string $locality
 * @property-read string $street
 * @property-read string $house
 * @property-read string $housing
 * @property-read string $building
 * @property-read string $addressString
 * @property-read string $shortAddressString
 */
final class Address
{
    use PropertyRead;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $locationId;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    private $latitude;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    private $longitude;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $postalCode;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $region;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $subRegion;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $locality;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $street;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $house;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $housing;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $building;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $addressString;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $shortAddressString;
}
