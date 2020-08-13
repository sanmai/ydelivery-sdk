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

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use function GuzzleHttp\default_user_agent;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

final class ClientBuilder implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const DEFAULT_TIMEOUT = 60;

    private const STANDARD_BASE_URL = 'https://api.delivery.yandex.ru';

    private const PACKAGE_NAME = 'YDelivery-SDK';
    private const VERSION_INFO = '$Format:%h%d by %an +%ae$';

    /** @var ClientInterface */
    private $http;

    /** @var string */
    private $token = '';

    /** @var string */
    private $baseUrl = self::STANDARD_BASE_URL;

    /** @var int */
    private $timeout = self::DEFAULT_TIMEOUT;

    /** @var string|null */
    private $cacheDirectory = null;

    /** @var bool */
    private $cacheDebug = false;

    /** @var SerializerInterface|Serialization\Serializer */
    private $serializer;

    /** @var string|null */
    private $userAgentPostfix;

    /** @var array */
    private $extraOptions = [];

    public static function clientWithToken(string $token = '', int $timeout = self::DEFAULT_TIMEOUT): Client
    {
        $builder = new self();
        $builder->setToken($token);
        $builder->setTimeout($timeout);

        return $builder->build();
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @see https://jmsyst.com/libs/serializer/master/configuration#configuring-a-cache-directory
     */
    public function setCacheDir(string $cacheDirectory = null, bool $debug = false)
    {
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheDebug = $debug;

        return $this;
    }

    public function build(): Client
    {
        $this->serializer = $this->serializer ?? new Serialization\Serializer($this->cacheDirectory);

        $this->http = $this->http ?? new GuzzleClient(\array_merge([
            'base_uri' => $this->baseUrl,
            'timeout'  => $this->timeout,
            'headers'  => [
                'Authorization' => "OAuth {$this->token}",
                'User-Agent'    => $this->getDefaultUserAgent(),
            ],
        ], $this->extraOptions));

        $client = new Client($this->http, $this->serializer);

        if ($this->logger !== null) {
            $client->setLogger($this->logger);
        }

        return $client;
    }

    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function setGuzzleClientExtraOptions(array $extraOptions)
    {
        $this->extraOptions = $extraOptions;

        return $this;
    }

    public function setUserAgent(string $product, string $versionDetails)
    {
        $this->userAgentPostfix = \sprintf('%s/%s', $product, $versionDetails);

        return $this;
    }

    public function setGuzzleClient(ClientInterface $http)
    {
        $this->http = $http;

        return $this;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDefaultUserAgent(): string
    {
        if ($this->userAgentPostfix === null) {
            $this->setUserAgent(self::PACKAGE_NAME, self::getVersion());
        }

        \assert(\is_string($this->userAgentPostfix));

        return default_user_agent().' '.$this->userAgentPostfix;
    }

    /**
     * @codeCoverageIgnore
     * @psalm-suppress MixedArrayAccess
     */
    private static function getVersion(): string
    {
        if (self::VERSION_INFO[0] === '$' && \is_dir(__DIR__.'/../.git')) {
            return (string) \exec(\sprintf('git --git-dir=%s describe --tags --dirty=-dev --always', \escapeshellarg(__DIR__.'/../.git')));
        }

        /** @var $parts string[] */
        if (\preg_match('/^([0-9a-f]+).*?tag: (v?[\d\.]+)\)(.*)/', self::VERSION_INFO, $parts)) {
            return "{$parts[2]}-{$parts[1]}{$parts[3]}";
        }

        /** @phan-suppress-next-line PhanTypeArraySuspiciousNullable */
        return (string) @\json_decode((string) \file_get_contents(__DIR__.'/../composer.json'), true)['extra']['branch-alias']['dev-master'];
    }
}
