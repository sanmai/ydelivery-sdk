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

if (false) {
    include 'examples/020_ResponseErrorHandling.php';
}

/**
 * @covers \YDeliverySDK\Client
 *
 * @see
 *
 * @group integration
 */
final class ErrorsTest extends TestCase
{
    /**
     * Случай ошибки авторизации с ошибочным токеном.
     */
    public function test_error_handling_with_invalid_token()
    {
        $request = new \YDeliverySDK\Requests\DeliveryServicesRequest();

        $builder = new \YDeliverySDK\ClientBuilder();
        $builder->setToken('invalid');
        $client = $builder->build();

        $response = $client->sendDeliveryServicesRequest($request);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('401', $message->getErrorCode());
            $this->assertSame('Unauthorized', $message->getMessage());
        }
    }

    /**
     * Случай корректного токена, но некорректных данных в запросе.
     */
    public function test_error_handling_with_invalid_data_but_correct_token()
    {
        $request = new \YDeliverySDK\Requests\DeliveryServicesRequest();
        $request->setCabinetId(1);

        $response = $this->getClient()->sendDeliveryServicesRequest($request);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());

        // Обрабатываем ошибки
        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getErrorCode());
            $this->assertNotEmpty($message->getMessage());
        }
    }

    /**
     * Случай отсутствующего параметра.
     */
    public function test_error_handling_with_missing_fields()
    {
        $request = new \YDeliverySDK\Requests\WithdrawIntervalsRequest();
        $request->setDateObject(new \DateTime('next Monday'));

        $response = $this->getClient()->sendWithdrawIntervalsRequest($request);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getErrorCode());
            $this->assertNotEmpty($message->getMessage());
        }
    }
}