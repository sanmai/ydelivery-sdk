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

use CommonSDK\Contracts\Response;
use Countable;
use IteratorAggregate;
use function Pipeline\map;
use YDeliverySDK\Client;
use YDeliverySDK\Requests\OrdersSearchRequest;

/**
 * @internal
 *
 * @property-read int $totalElements Количество объектов в ответе.
 * @property-read int $totalPages Количество страниц в ответе.
 * @property-read int $size	Количество объектов на странице.
 * @property-read int $pageNumber Номер текущей страницы (начиная с 0).
 */
final class OrdersSearchResponseIterator implements Response, Countable, IteratorAggregate
{
    private $client;
    private $request;

    /** @var Response|null */
    private $response;

    public function __construct(Client $client, OrdersSearchRequest $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    private function makeRequest(): void
    {
        $this->response = $this->client->sendOrdersSearchRequest($this->request);
        $this->request->addPage();
    }

    /**
     * @return OrdersSearchResponse
     */
    private function getLastResponse()
    {
        if ($this->response === null) {
            $this->makeRequest();
        }

        return $this->response;
    }

    public function getIterator()
    {
        return map(function () {
            do {
                yield from $this->getLastResponse();
                $this->makeRequest();
            } while ($this->getLastResponse()->pageNumber < $this->getLastResponse()->lastPageNumber);

            yield from $this->getLastResponse();
        });
    }

    public function hasErrors(): bool
    {
        return $this->getLastResponse()->hasErrors();
    }

    public function getMessages()
    {
        return $this->getLastResponse()->getMessages();
    }

    public function count()
    {
        return $this->getLastResponse()->totalElements;
    }

    public function __get(string $property)
    {
        return $this->getLastResponse()->{$property};
    }
}
