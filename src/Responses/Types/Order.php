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
use CommonSDK\Concerns\SuccessfulResponse;
use CommonSDK\Contracts\Response;
use DateTimeImmutable;
use JMS\Serializer\Annotation as JMS;

/**
 * @property-read int $id
 * @property-read string $externalId
 * @property-read string $comment
 * @property-read DateTimeImmutable $created
 * @property-read string $status
 * @property-read string $deliveryType
 * @property-read string $deliveryServiceExternalId
 * @property-read Order\Sender $sender
 * @property-read Order\DeliveryOption $deliveryOption
 * @property-read bool $hasLabel
 * @property-read Order\AvailableActions $availableActions
 */
final class Order implements Response
{
    use SuccessfulResponse;
    use PropertyRead;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $id;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $externalId;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $comment;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s.uO'>")
     *
     * @var DateTimeImmutable
     */
    private $created;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $status;

    /**
     * @JMS\Exclude
     */
    private $cost;

    /**
     * @JMS\Exclude
     */
    private $places = [];

    /**
     * @JMS\Exclude
     */
    private $contacts = [];

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $deliveryType;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $deliveryServiceExternalId;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\Order\Sender")
     *
     * @var Order\Sender
     */
    private $sender;

    /**
     * @JMS\Exclude
     */
    private $recipient;

    /**
     * @JMS\Exclude
     */
    private $shipment;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\Order\DeliveryOption")
     *
     * @var Order\DeliveryOption
     */
    private $deliveryOption;

    /**
     * @JMS\Type("bool")
     *
     * @var bool
     */
    private $hasLabel;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\Order\AvailableActions")
     *
     * @var Order\AvailableActions
     */
    private $availableActions;
}
