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
use YDeliverySDK\Responses\Bad\BadRequestResponse;
use YDeliverySDK\Responses\Bad\NotFoundResponse;
use YDeliverySDK\Responses\Bad\UnauthorizedResponse;

/**
 * @covers \YDeliverySDK\Responses\Bad\UnauthorizedResponse
 * @covers \YDeliverySDK\Responses\Bad\NotFoundResponse
 * @covers \YDeliverySDK\Responses\Bad\BadRequestResponse
 */
class ErrorResponsesTest extends TestCase
{
    public function errorResponsesProvider()
    {
        yield '400_Bad_Request.json' => ['400_Bad_Request.json', BadRequestResponse::class, [
            ['UNKNOWN', "Required Long parameter 'partnerId' is not present"],
        ]];

        yield '401_Unauthorized.json' => ['401_Unauthorized.json', UnauthorizedResponse::class,  [
            ['401', 'Unauthorized'],
        ]];

        yield '404_Not_Found.json' => ['404_Not_Found.json', NotFoundResponse::class, [
            ['RESOURCE_NOT_FOUND', 'Failed to find [SHOP] with ids [1]'],
        ]];

        yield '400_Validation.json' => ['400_Validation.json', BadRequestResponse::class, [
            ['VALIDATION_ERROR', 'Validation error'],
            ['FIELD_NOT_VALID', 'senderOrderDraft->deliveryType must not be null'],
            ['FIELD_NOT_VALID', 'senderOrderDraft->senderId must not be null'],
        ]];

        yield '400_DELIVERY_OPTION_VALIDATION.json' => ['400_DELIVERY_OPTION_VALIDATION.json', BadRequestResponse::class, [
            ['DELIVERY_OPTION_VALIDATION', 'Delivery option validation failed'],
            ['OPTION_SERVICE_NOT_FOUND', 'DELIVERY'],
            ['OPTION_SERVICE_NOT_FOUND', 'CASH_SERVICE'],
            ['OPTION_SERVICE_NOT_FOUND', 'RETURN'],
            ['OPTION_SERVICE_NOT_FOUND', 'RETURN_SORT'],
            ['OPTION_CALCULATED_DATE_MIN_MISMATCH', '2020-10-31'],
            ['OPTION_CALCULATED_DATE_MAX_MISMATCH', '2020-10-31'],
        ]];
    }

    /**
     * @dataProvider errorResponsesProvider
     */
    public function test_it_can_be_read(string $fixtureFileName, string $typeName, array $expectedErrors)
    {
        $response = $this->loadFixtureWithType($fixtureFileName, $typeName);
        /** @var $response NotFoundResponse|UnauthorizedResponse|BadRequestResponse */
        $this->assertInstanceOf(Response::class, $response);

        $this->assertCount(0, $response);
        $this->assertTrue($response->hasErrors());

        $this->assertSame(
            $expectedErrors,
            take($response->getMessages())->map(function (HasErrorCode $message) {
                return [
                    $message->getErrorCode(),
                    $message->getMessage(),
                ];
            })->toArray()
        );
    }
}
