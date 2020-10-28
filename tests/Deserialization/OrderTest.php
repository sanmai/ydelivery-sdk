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

namespace Tests\YDeliverySDK\Deserialization;

use YDeliverySDK\Responses\Types\Order;

/**
 * @covers \YDeliverySDK\Responses\Types\Order
 */
class OrderTest extends TestCase
{
    public function test_empty_order()
    {
        $order = $this->loadFixture('empty_order.json');

        $this->assertFalse($order->hasErrors());

        $this->assertSame('Пустой тестовый заказ.', $order->comment);
        $this->assertSame('COURIER', $order->deliveryType);
        $this->assertSame(3553000, $order->id);
        $this->assertSame(500002300, $order->sender->id);
        $this->assertSame('Example', $order->sender->name);
        $this->assertSame('2020-10-10', $order->created->format('Y-m-d'));
        $this->assertSame('DRAFT', $order->status);

        $this->assertNull($order->deliveryOption->tariffId);

        $this->assertFalse($order->hasLabel);

        $this->assertFalse($order->availableActions->untieFromShipment);
        $this->assertFalse($order->availableActions->generateLabel);
    }

    private function loadFixture(string $filename): Order
    {
        return $this->loadFixtureWithType($filename, Order::class);
    }
}
