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

use YDeliverySDK\Responses\PickupPointsResponse;
use YDeliverySDK\Responses\Types\PickupPoint;

/**
 * @covers \YDeliverySDK\Responses\PickupPointsResponse
 * @covers \YDeliverySDK\Responses\Types\PickupPoint
 */
class PickupPointsResponseTest extends TestCase
{
    public function test_successful_request()
    {
        $response = $this->loadFixture('pickup-points.json');

        $this->assertFalse($response->hasErrors());

        $this->assertCount(2, $response);

        foreach ($response as $item) {
            /** @var $item PickupPoint */
            $this->assertInstanceOf(PickupPoint::class, $item);

            $this->assertGreaterThan(0, $item->id);
            $this->assertGreaterThan(0, $item->partnerId);
            $this->assertNotNull($item->type);
            $this->assertSame(6, \strlen($item->address->postalCode));
            $this->assertGreaterThan(0, $item->address->locationId);
            $this->assertGreaterThan(0, $item->address->latitude);
            $this->assertGreaterThan(0, $item->address->longitude);

            foreach ($item->phones as $phone) {
                $this->assertNotNull($phone->type);
                $this->assertIsString($phone->number);
            }

            foreach ($item->schedule as $day) {
                $this->assertGreaterThan(0, $day->day);
                $this->assertIsString($day->from);
                $this->assertIsString($day->to);
            }

            $this->assertIsBool($item->supportedFeatures->cash);
        }
    }

    private function loadFixture(string $filename): PickupPointsResponse
    {
        return $this->loadFixtureWithType($filename, PickupPointsResponse::class);
    }
}
