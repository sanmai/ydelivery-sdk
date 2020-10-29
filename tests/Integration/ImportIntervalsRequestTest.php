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

use YDeliverySDK\Requests\DeliveryServicesRequest;
use YDeliverySDK\Requests\ImportIntervalsRequest;

/** @psalm-suppress TypeDoesNotContainType */
if (false) {
    include 'examples/070_ImportIntervals.php';
}

/**
 * @covers \YDeliverySDK\Requests\ImportIntervalsRequest
 *
 * @group integration
 */
final class ImportIntervalsRequestTest extends TestCase
{
    public function test_successful_request()
    {
        $client = $this->getClient();

        $request = new DeliveryServicesRequest();
        $request->cabinetId = $this->getCabinetId();

        $partner = null;

        // Получим ID первого попавшегося сервиса доставки.
        foreach ($client->sendDeliveryServicesRequest($request) as $partner) {
            break;
        }

        $this->assertNotNull($partner);

        // Для него получим расписание доставки.
        $request = new ImportIntervalsRequest();
        $request->date = new \DateTime('next Monday');

        // Используя первый попавшийся склад.
        foreach ($partner->warehouses as $warehouse) {
            $request->warehouseId = $warehouse->id;
        }

        $resp = $client->sendImportIntervalsRequest($request);

        $this->assertGreaterThan(0, \count($resp));

        foreach ($resp as $value) {
            $this->assertNotEmpty($value->id);
            $this->assertNotEmpty($value->from);
            $this->assertNotEmpty($value->to);
        }
    }
}
