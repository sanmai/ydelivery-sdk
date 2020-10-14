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

use YDeliverySDK\Requests\DeliveryOptionsRequest;
use YDeliverySDK\Requests\Types\Address;
use YDeliverySDK\Requests\Types\Cost;
use YDeliverySDK\Requests\Types\Dimensions;
use YDeliverySDK\Requests\Types\Shipment;

/**
 * @covers \YDeliverySDK\Requests\DeliveryOptionsRequest
 */
final class DeliveryOptionsRequestTest extends TestCase
{
    public function test_constructor_default_arguments()
    {
        $request = new DeliveryOptionsRequest();

        $this->assertInstanceOf(Address::class, $request->from);
        $this->assertInstanceOf(Address::class, $request->to);
        $this->assertInstanceOf(Dimensions::class, $request->dimensions);
        $this->assertInstanceOf(Shipment::class, $request->shipment);
        $this->assertInstanceOf(Cost::class, $request->cost);
    }

    public function test_constructor_injected_arguments()
    {
        $from = new Address();
        $to = new Address();
        $dimensions = new Dimensions();
        $shipment = new Shipment();
        $cost = new Cost();

        $request = new DeliveryOptionsRequest($from, $to, $dimensions, $shipment, $cost);

        $this->assertSame($from, $request->from);
        $this->assertSame($to, $request->to);
        $this->assertSame($dimensions, $request->dimensions);
        $this->assertSame($shipment, $request->shipment);
        $this->assertSame($cost, $request->cost);
    }
}
