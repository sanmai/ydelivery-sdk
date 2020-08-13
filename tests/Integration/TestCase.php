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

use YDeliverySDK\Client;
use YDeliverySDK\ClientBuilder;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    const YANDEX_SHOP_ID = 'YANDEX_SHOP_ID';
    const YANDEX_CABINET_ID = 'YANDEX_CABINET_ID';
    const YANDEX_DELIVERY_TOKEN = 'YANDEX_DELIVERY_TOKEN';
    const YANDEX_WAREHOUSE_ID = 'YANDEX_WAREHOUSE_ID';

    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress PossiblyFalseArgument
     */
    final protected function getClient(): Client
    {
        $builder = new ClientBuilder();
        $builder->setToken(self::getEnvOrSkipTest(self::YANDEX_DELIVERY_TOKEN));

        if (\is_dir('build/cache/')) {
            $builder->setCacheDir('build/cache/', true);
        }

        if (\in_array('--debug', $_SERVER['argv'])) {
            $builder->setLogger(new DebuggingLogger());
        }

        return $builder->build();
    }

    final protected function getCabinetId(): int
    {
        return (int) self::getEnvOrSkipTest(self::YANDEX_CABINET_ID);
    }

    final protected function getShopId(): int
    {
        return (int) self::getEnvOrSkipTest(self::YANDEX_SHOP_ID);
    }

    private static function getEnvOrSkipTest(string $varname): string
    {
        if (false === \getenv($varname)) {
            self::markTestSkipped(\sprintf('Integration testing disabled (%s missing).', $varname));
        }

        return \getenv($varname);
    }
}
