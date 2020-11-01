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

use CommonSDK\Contracts\HasErrorCode;
use CommonSDK\Contracts\Response;
use function Pipeline\take;
use YDeliverySDK\Responses\SubmitOrderResponse;
use YDeliverySDK\Responses\Types\SubmittedOrder;

/**
 * @covers \YDeliverySDK\Responses\SubmitOrderResponse
 * @covers \YDeliverySDK\Responses\Types\SubmittedOrder
 * @covers \YDeliverySDK\Responses\Types\SubmittedOrder\Error
 */
class SubmitOrderResponseTest extends TestCase
{
    public function test_with_violations()
    {
        $response = $this->loadFixture('submit-order-error.json');

        $this->assertCount(1, $response);
        $this->assertTrue($response->hasErrors());

        $order = take($response)->toArray()[0];

        $this->assertInstanceOf(SubmittedOrder::class, $order);

        /** @var SubmittedOrder $order */
        $this->assertSame(3000001, $order->orderId);
        $this->assertSame('VALIDATION_ERROR', $order->status);

        $this->assertSame(
            [
                ['INCOMPLETE_ORDER', 'places.dimensions.height'],
                ['INCOMPLETE_ORDER', 'places.dimensions.width'],
                ['INCOMPLETE_ORDER', 'places.dimensions.length'],
                ['INCOMPLETE_ORDER', 'places.dimensions.weight'],
                ['INCOMPLETE_ORDER', 'deliveryOption.tariffId'],
                ['INCOMPLETE_ORDER', 'recipient.address'],
                ['INCOMPLETE_ORDER', 'shipment.partnerTo'],
                ['INCOMPLETE_ORDER', 'shipment.type'],
            ],
            $this->toErrorsArray($response)
        );
    }

    public function test_with_validation_error()
    {
        $response = $this->loadFixture('submit-order-error2.json');

        $this->assertCount(1, $response);
        $this->assertTrue($response->hasErrors());

        $order = take($response)->toArray()[0];

        $this->assertInstanceOf(SubmittedOrder::class, $order);

        /** @var SubmittedOrder $order */
        $this->assertSame(3000002, $order->orderId);
        $this->assertSame('VALIDATION_ERROR', $order->status);

        $this->assertSame(
            [
                ['VALIDATION_ERROR', 'waybill[0] must be assigned to a shipment'],
            ],
            $this->toErrorsArray($response)
        );
    }

    public function test_empty()
    {
        $response = $this->loadFixture('empty.json');

        $this->assertCount(0, $response);
        $this->assertFalse($response->hasErrors());
    }

    private function toErrorsArray(Response $response): array
    {
        return take($response->getMessages())->map(function (HasErrorCode $message) {
            yield [
                $message->getErrorCode(),
                $message->getMessage(),
            ];
        })->toArray();
    }

    private function loadFixture(string $filename): SubmitOrderResponse
    {
        return $this->loadFixtureWithType($filename, SubmitOrderResponse::class);
    }
}
