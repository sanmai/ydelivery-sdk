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

namespace YDeliverySDK\Responses\Types\DeliveryOption;

use JMS\Serializer\Annotation as JMS;

final class Delivery
{
    /*
        "type": "POST",
        "calculatedDeliveryDateMin": "2020-08-21",
        "calculatedDeliveryDateMax": "2020-08-24",
        "courierSchedule": null

     */

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\DeliveryOption\Partner")
     *
     * @var Partner
     */
    private $partner;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $type;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    private $calculatedDeliveryDateMin;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    private $calculatedDeliveryDateMax;

    /**
     * @JMS\Type("YDeliverySDK\Responses\Types\DeliveryOption\CourierSchedule")
     *
     * @var CourierSchedule|null
     */
    private $courierSchedule;

    /**
     * Служба доставки.
     */
    public function getPartner(): Partner
    {
        return $this->partner;
    }

    /**
     * Тип доставки: COURIER — курьером, PICKUP — в пункт выдачи, POST — по почте.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Начальная дата доставки.
     */
    public function getCalculatedDeliveryDateMin(): \DateTimeInterface
    {
        return $this->calculatedDeliveryDateMin;
    }

    /**
     * Конечная дата доставки в формате.
     */
    public function getCalculatedDeliveryDateMax(): \DateTimeInterface
    {
        return $this->calculatedDeliveryDateMax;
    }

    /**
     * Расписание работы курьеров партнера.
     */
    public function getCourierSchedule(): ?CourierSchedule
    {
        return $this->courierSchedule;
    }
}
