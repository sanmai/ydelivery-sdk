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
use function Pipeline\zip;
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
        ]];
    }

    /**
     * @dataProvider errorResponsesProvider
     */
    public function test_it_can_be_read(string $fixtureFileName, string $typeName, array $errors)
    {
        $response = $this->loadFixtureWithType($fixtureFileName, $typeName);
        /** @var $response NotFoundResponse|UnauthorizedResponse|BadRequestResponse */
        $this->assertInstanceOf(Response::class, $response);

        $this->assertCount(0, $response);
        $this->assertTrue($response->hasErrors());

        $this->assertCount(\count($errors), $response->getMessages());

        $this->assertCount(\count($errors), zip($errors, $response->getMessages())
            ->unpack(function (array $expected, HasErrorCode $message) {
                $this->assertSame($expected[0], $message->getErrorCode());
                $this->assertSame($expected[1], $message->getMessage());
            })
        );
    }
}
