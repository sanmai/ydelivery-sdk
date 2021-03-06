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

namespace Tests\YDeliverySDK\Serialization;

use YDeliverySDK\Requests\SubmitOrderRequest;

/**
 * @covers \YDeliverySDK\Requests\SubmitOrderRequest
 */
final class SubmitOrderRequestTest extends TestCase
{
    public function test_it_can_have_list_of_orders()
    {
        $request = new SubmitOrderRequest();
        $request->orderIds = [123, 456];

        $this->assertSameAsJSON('{"orderIds":[123,456]}', $request);
    }

    public function test_it_can_have_added_orders()
    {
        $request = new SubmitOrderRequest();
        $request->addOrder(111)->addOrder(222);

        $this->assertSameAsJSON('{"orderIds":[111,222]}', $request);
    }

    public function test_it_can_add_order_ids()
    {
        $request = new SubmitOrderRequest([123]);
        /** @phpstan-ignore-next-line */
        $request->orderIds[] = 456;

        $this->assertSameAsJSON('{"orderIds":[123,456]}', $request);
    }
}
