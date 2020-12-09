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

namespace Tests\YDeliverySDK;

use CommonSDK\Types\Client as CommonClient;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Foundation\Application as ApplicationInterface;
use PHPUnit\Framework\TestCase;
use YDeliverySDK\Client;
use YDeliverySDK\LaravelServiceProvider;

/**
 * @covers \YDeliverySDK\LaravelServiceProvider
 */
class LaravelServiceProviderTest extends TestCase
{
    /**
     * @var ApplicationInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $app;

    /**
     * @var LaravelServiceProvider
     */
    private $provider;

    /** @var ClientInterface */
    private $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createMock(ApplicationInterface::class);
        $this->provider = new LaravelServiceProvider($this->app);
    }

    private function applicationWithConfig(array $config): \Illuminate\Foundation\Application
    {
        $app = new class() extends \Illuminate\Foundation\Application {
            private $config;

            public function __construct($basePath = null)
            {
                if (false) {
                    // PHPStan workaround
                    parent::__construct($basePath);
                }
            }

            public function setConfig($config)
            {
                $this->config = $config;
            }

            public function offsetGet($key)
            {
                TestCase::assertSame('config', $key);

                return $this->config;
            }
        };

        $app->setConfig($config);

        return $app;
    }

    private function runOnClient(Client $client, callable $callback)
    {
        return \Closure::bind($callback, $client, CommonClient::class)();
    }

    public function test_register()
    {
        $savedCallback = null;

        $this->app->expects($this->once())
            ->method('singleton')
            ->with(Client::class)->will($this->returnCallback(function ($className, $callback) use (&$savedCallback) {
                $savedCallback = $callback;
            }));

        $this->provider->register();

        $this->assertNotNull($savedCallback);

        return $savedCallback;
    }

    /**
     * @depends test_register
     */
    public function test_with_minimal_config(callable $savedCallback)
    {
        $client = $savedCallback($this->applicationWithConfig([
            'services.ydelivery' => [
                'token'  => 'foo',
            ],
        ]));

        $this->assertInstanceOf(Client::class, $client);

        $this->assertStringContainsString('foo', $this->runOnClient($client, function () {
            return $this->http->getConfig()['headers']['Authorization'];
        }));
    }

    /**
     * @depends test_register
     */
    public function test_with_custom_timeout(callable $savedCallback)
    {
        $client = $savedCallback($this->applicationWithConfig([
            'services.ydelivery' => [
                'token'   => 'bar',
                'timeout' => 100000,
                'cache'   => \sys_get_temp_dir(),
            ],
        ]));

        $this->assertInstanceOf(Client::class, $client);

        $this->assertStringContainsString('bar', $this->runOnClient($client, function () {
            return $this->http->getConfig()['headers']['Authorization'];
        }));

        $this->assertEquals(100000, $this->runOnClient($client, function () {
            return $this->http->getConfig()['timeout'];
        }));
    }

    public function test_provides()
    {
        $this->assertSame([Client::class], $this->provider->provides());
    }
}
