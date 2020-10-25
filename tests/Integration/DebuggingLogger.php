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

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

final class DebuggingLogger implements LoggerInterface
{
    use LoggerTrait;

    /** @var string[] */
    private $filename = [];

    /**
     * @param mixed  $level
     * @param string $message
     *
     * @psalm-suppress MixedTypeCoercion
     * @psalm-suppress TypeDoesNotContainType
     */
    public function log($level, $message, array $context = [])
    {
        if ($context) {
            $message = \strtr($message, \iterator_to_array(self::context2replacements($context), true));
        }

        if (\strpos($message, 'WITHDRAW') !== false) {
            // Заявка на Забор (WITHDRAW) тарифицируется сразу же как была подтверждена службой доставки.
            \trigger_error('Forbidden request', E_USER_ERROR);
        }

        // В целях отладки приведём JSON в читаемый вид
        if (\strpos($message, '{') === 0 || \strpos($message, '[') === 0) {
            $message = \json_encode(\json_decode($message), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($this->filename !== []) {
                $saveTo = \array_shift($this->filename);
                \file_put_contents($saveTo, $message);
                \fwrite(\STDERR, "\n\n(Saved below message to $saveTo)");
            }
        }

        \fwrite(\STDERR, "\n{$message}\n\n");
    }

    public function addFile(string $filename): void
    {
        $this->filename[] = $filename;
    }

    /**
     * @param array<string, string> $context
     */
    private static function context2replacements($context): \Generator
    {
        foreach ($context as $key => $value) {
            yield '{'.$key.'}' => $value;
        }
    }
}
