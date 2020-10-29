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

namespace Tests\YDeliverySDK\Integration;

use YDeliverySDK\Requests\PickupPointsRequest;

/** @psalm-suppress TypeDoesNotContainType */
if (false) {
    include 'examples/035_PickupPoints.php';
}

/**
 * @covers \YDeliverySDK\Requests\PickupPointsRequest
 *
 * @group integration
 */
final class PickupPointsRequestTest extends TestCase
{
    public function test_successful_request()
    {
        $request = new PickupPointsRequest();
        $request->type = $request::TYPE_TERMINAL;
        $request->locationId = 65;
        $request->latitude->from = 55.013;
        $request->latitude->to = 55.051;
        $request->longitude->from = 82.951;
        $request->longitude->to = 83.081;

        $response = $this->getClient()->sendPickupPointsRequest($request);

        $this->assertGreaterThan(0, \count($response));
        foreach ($response as $item) {
            $this->assertGreaterThan(0, $item->id);
            $this->assertGreaterThan(0, $item->partnerId);
            $this->assertNotNull($item->type);
            $this->assertSame(6, \strlen($item->address->postalCode));
            $this->assertGreaterThan(0, $item->address->locationId);
            $this->assertGreaterThan(0, $item->address->latitude);
            $this->assertGreaterThan(0, $item->address->longitude);
        }
    }
}
