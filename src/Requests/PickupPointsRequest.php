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

namespace YDeliverySDK\Requests;

use CommonSDK\Concerns\ObjectPropertyRead;
use CommonSDK\Concerns\PropertyWrite;
use CommonSDK\Concerns\RequestCore;
use CommonSDK\Contracts\JsonRequest;
use CommonSDK\Types\ArrayProperty;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Requests\Types\GeographicCoordinateRange;
use YDeliverySDK\Responses\PickupPointsResponse;

/**
 * @property-write int[] $pickupPointIds Идентификаторы пунктов выдачи (для типов доставки — в пункт выдачи и на почту).
 * @property-read GeographicCoordinateRange $latitude Диапазон широты.
 * @property-read GeographicCoordinateRange $longitude Диапазон долготы.
 * @property-write int $locationId Идентификатор населенного пункта.
 * @property-write string $type Тип пункта выдачи заказов.
 *
 * @phan-file-suppress PhanAccessWriteOnlyMagicProperty
 */
final class PickupPointsRequest implements JsonRequest
{
    use RequestCore;
    use ObjectPropertyRead;
    use PropertyWrite;

    /** пункт выдачи */
    public const TYPE_PICKUP_POINT = 'PICKUP_POINT';

    /** постамат */
    public const TYPE_TERMINAL = 'TERMINAL';

    /** почтовое отделение */
    public const TYPE_POST_OFFICE = 'POST_OFFICE';

    /**
     * @JMS\Type("ArrayCollection<int>")
     * @JMS\SkipWhenEmpty
     *
     * @var ArrayProperty<int>
     */
    private $pickupPointIds;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Types\GeographicCoordinateRange")
     * @JMS\SkipWhenEmpty
     *
     * @var GeographicCoordinateRange
     */
    private $latitude;

    /**
     * @JMS\Type("YDeliverySDK\Requests\Types\GeographicCoordinateRange")
     * @JMS\SkipWhenEmpty
     *
     * @var GeographicCoordinateRange
     */
    private $longitude;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $locationId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $type;

    private const METHOD = 'PUT';
    private const ADDRESS = '/pickup-points';

    private const RESPONSE = PickupPointsResponse::class;

    /**
     * @phan-suppress PhanTypeMismatchPropertyProbablyReal
     *
     * @param int[] $pickupPointIds
     */
    public function __construct(array $pickupPointIds = [], ?GeographicCoordinateRange $latitude = null, ?GeographicCoordinateRange $longitude = null)
    {
        $this->pickupPointIds = new ArrayProperty($pickupPointIds);
        $this->latitude = $latitude ?? new GeographicCoordinateRange();
        $this->longitude = $longitude ?? new GeographicCoordinateRange();
    }
}
