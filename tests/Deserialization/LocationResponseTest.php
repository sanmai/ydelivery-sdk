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

use YDeliverySDK\Responses\LocationResponse;
use YDeliverySDK\Responses\Types\Location;

/**
 * @covers \YDeliverySDK\Responses\LocationResponse
 * @covers \YDeliverySDK\Responses\Types\Location
 * @covers \YDeliverySDK\Responses\Types\Location\AddressComponent
 */
class LocationResponseTest extends TestCase
{
    public function commonResponsesProvider(): iterable
    {
        yield ['locations.json', 3];
    }

    /**
     * @dataProvider commonResponsesProvider
     */
    public function test_successful_request(string $fixtureName, int $count)
    {
        $response = $this->loadFixture($fixtureName);

        $this->assertFalse($response->hasErrors());

        $this->assertCount($count, $response);

        foreach ($response as $item) {
            /** @var $item Location */
            $this->assertInstanceOf(Location::class, $item);

            $this->assertGreaterThan(0, $item->geoId);
            $this->assertGreaterThan(0, \strlen($item->address));

            $this->assertCount(5, $item->addressComponents);

            foreach ($item->addressComponents as $component) {
                $this->assertIsString($component->kind);
                $this->assertIsString($component->name);
                $this->assertSame($component->name, (string) $component);
            }
        }
    }

    public function test_decode_location()
    {
        $location = $this->getSerializer()->deserialize('{"geoId":192,"address":"Россия, Владимир","addressComponents":[]}', Location::class);
        /** @var $location Location */
        $this->assertSame(192, $location->geoId);
        $this->assertSame('Россия, Владимир', $location->address);
        $this->assertCount(0, $location->addressComponents);
    }

    private function loadFixture(string $filename): LocationResponse
    {
        return $this->loadFixtureWithType($filename, LocationResponse::class);
    }
}
