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

namespace YDeliverySDK\Responses;

use ArrayIterator;
use CommonSDK\Concerns\PropertyRead;
use CommonSDK\Concerns\SuccessfulResponse;
use CommonSDK\Contracts\Response;
use Countable;
use IteratorAggregate;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Responses\Types\Order;

/**
 * @property-read Order[] $data
 * @property-read int $totalElements Количество объектов в ответе.
 * @property-read int $totalPages Количество страниц в ответе.
 * @property-read int $size	Количество объектов на странице.
 * @property-read int $pageNumber Номер текущей страницы (начиная с 0).
 * @property-read int $lastPageNumber Номер последней страницы.
 *
 * @template-implements \IteratorAggregate<Order>
 */
final class OrdersSearchResponse implements Response, Countable, IteratorAggregate
{
    use PropertyRead;
    use SuccessfulResponse;

    /**
     * @JMS\Type("array<YDeliverySDK\Responses\Types\Order>")
     *
     * @var Order[]
     */
    private $data;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $totalElements;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $totalPages;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $size;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $pageNumber;

    public function count()
    {
        return \count($this->data);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    protected function getLastPageNumber(): int
    {
        return $this->totalPages - 1;
    }
}
