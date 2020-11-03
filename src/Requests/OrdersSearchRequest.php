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
use CommonSDK\Contracts\ParamRequest;
use CommonSDK\Types\ArrayProperty;
use JMS\Serializer\Annotation as JMS;
use YDeliverySDK\Requests\Templates\PagingRequest;
use YDeliverySDK\Requests\Types\StatusList;
use YDeliverySDK\Responses\OrdersSearchResponse;

/**
 * @property-write int[] $senderIds Идентификаторы магазинов. Обязательный параметр.
 * @property-write int[] $orderIds Список идентификаторов заказов.
 * @property-write int[] $partnerIds Идентификаторы служб доставки.
 * @property StatusList|string[] $statuses Коды статусов заказа.
 * @property-write string $term Поисковый запрос. Разбивается по пробелам на слова, каждое из которых ищется по вхождению без учета регистра в полях заказа.
 * @property-write int $page Номер текущей страницы (начиная с 0).
 * @property-write int $size Количество объектов на странице.
 *
 * @phan-file-suppress PhanAccessWriteOnlyMagicProperty
 */
final class OrdersSearchRequest implements JsonRequest, ParamRequest, PagingRequest
{
    use RequestCore;
    use ObjectPropertyRead;
    use PropertyWrite;

    /**
     * @JMS\Type("ArrayCollection<int>")
     * @JMS\SkipWhenEmpty
     *
     * @var ArrayProperty<int>
     */
    private $senderIds;

    /**
     * @JMS\Type("ArrayCollection<int>")
     * @JMS\SkipWhenEmpty
     *
     * @var ArrayProperty<int>
     */
    private $orderIds;

    /**
     * @JMS\Type("ArrayCollection<int>")
     * @JMS\SkipWhenEmpty
     *
     * @var ArrayProperty<int>
     */
    private $partnerIds;

    /**
     * @JMS\Type("ArrayCollection<string>")
     * @JMS\SkipWhenEmpty
     *
     * @var StatusList
     */
    private $statuses;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $term;

    /**
     * @JMS\Exclude
     *
     * @var int
     */
    private $page = 0;

    /**
     * @JMS\Exclude
     *
     * @var int|null
     */
    private $size;

    private const METHOD = 'PUT';
    private const ADDRESS = '/orders/search';

    private const RESPONSE = OrdersSearchResponse::class;

    /**
     * @phan-suppress PhanTypeMismatchPropertyProbablyReal
     *
     * @param int[]    $senderIds
     * @param int[]    $orderIds
     * @param int[]    $partnerIds
     * @param string[] $statuses
     */
    public function __construct(array $senderIds, array $orderIds = [], array $partnerIds = [], array $statuses = [])
    {
        $this->senderIds = new ArrayProperty($senderIds);
        $this->orderIds = new ArrayProperty($orderIds);
        $this->partnerIds = new ArrayProperty($partnerIds);
        $this->statuses = new StatusList($statuses);
    }

    public function addPage(): void
    {
        ++$this->page;
    }

    public function getParams(): array
    {
        return \array_filter([
            'page' => $this->page,
            'size' => $this->size,
        ], function ($val) {
            return $val !== null;
        });
    }
}
