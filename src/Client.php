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

namespace YDeliverySDK;

use CommonSDK\Types\Client as CommonClient;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use YDeliverySDK\Responses\Bad\BadRequestResponse;
use YDeliverySDK\Responses\Bad\NotFoundResponse;
use YDeliverySDK\Responses\Bad\UnauthorizedResponse;

/**
 * Class Client.
 *
 * @method Responses\DeliveryServicesResponse|Responses\Types\DeliveryService[] sendDeliveryServicesRequest(Requests\DeliveryServicesRequest $request)
 * @method Responses\PostalCodeResponse|Responses\Types\PostalCode[]            sendPostalCodeRequest(Requests\PostalCodeRequest $request)
 * @method Responses\LocationResponse|Responses\Types\Location[]                sendLocationRequest(Requests\LocationRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendWithdrawIntervalsRequest(Requests\WithdrawIntervalsRequest $request)
 * @method Responses\IntervalsResponse|Responses\Types\Interval[]               sendImportIntervalsRequest(Requests\ImportIntervalsRequest $request)
 * @method Responses\DeliveryOptionsResponse|Responses\Types\DeliveryOption[]   sendDeliveryOptionsRequest(Requests\DeliveryOptionsRequest $request)
 * @method Responses\OrderResponse                                              sendCreateOrderRequest(Requests\CreateOrderRequest $request)
 * @method Responses\SubmitOrderResponse|Responses\Types\SubmittedOrder[]       sendSubmitOrderRequest(Requests\SubmitOrderRequest $request)
 */
final class Client extends CommonClient
{
    protected const ERROR_CODE_RESPONSE_CLASS_MAP = [
        HttpResponse::HTTP_UNAUTHORIZED   => UnauthorizedResponse::class,
        HttpResponse::HTTP_NOT_FOUND      => NotFoundResponse::class,
        HttpResponse::HTTP_BAD_REQUEST    => BadRequestResponse::class,
    ];
}
