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

namespace YDeliverySDK\Responses\Types;

use CommonSDK\Concerns\PropertyRead;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Responses\Types\PickupPoint\Address;
use YDeliverySDK\Responses\Types\PickupPoint\Contact;
use YDeliverySDK\Responses\Types\PickupPoint\DaySchedule;
use YDeliverySDK\Responses\Types\PickupPoint\Phone;
use YDeliverySDK\Responses\Types\PickupPoint\SupportedFeatures;

/**
 * @property-read int $id
 * @property-read int $partnerId
 * @property-read string $type
 * @property-read string $name
 * @property-read string $instruction
 * @property-read Address $address
 * @property-read Phone[] $phones
 * @property-read DaySchedule[] $schedule
 * @property-read Contact|null $contact
 * @property-read SupportedFeatures $supportedFeatures
 */
final class PickupPoint
{
    use PropertyRead;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $id;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $partnerId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $type;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $name;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $instruction;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\PickupPoint\Address")
     *
     * @var Address
     */
    private $address;

    /**
     * @JMS\Type("array<YDeliverySDK\Responses\Types\PickupPoint\Phone>")
     *
     * @var Phone[]
     */
    private $phones = [];

    /**
     * @JMS\Type("array<YDeliverySDK\Responses\Types\PickupPoint\DaySchedule>")
     *
     * @var DaySchedule[]
     */
    private $schedule = [];

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\PickupPoint\Contact")
     *
     * @var Contact|null
     */
    private $contact;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\PickupPoint\SupportedFeatures")
     *
     * @var SupportedFeatures
     */
    private $supportedFeatures;
}
