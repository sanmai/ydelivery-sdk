<?php
/**
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com> and contributors
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

namespace CdekSDK\Serialization;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

final class Serializer implements SerializerInterface
{
    /** @var bool */
    private static $configureAnnotationRegistry = true;

    /**
     * Настраивать ли AnnotationRegistry в автоматическом режиме, используя штатный автозагрузчик классов.
     */
    public static function doNotConfigureAnnotationRegistry()
    {
        self::$configureAnnotationRegistry = false;
    }

    /** @var SerializerInterface */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistryInterface $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();

        // Ignore Phan/Psalm issue-suppressing annotations
        AnnotationReader::addGlobalIgnoredName('phan');
        AnnotationReader::addGlobalIgnoredName('psalm');

        if (self::$configureAnnotationRegistry) {
            self::configureAnnotationRegistry();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \JMS\Serializer\SerializerInterface::serialize()
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function serialize($data, $format = null, SerializationContext $context = null)
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     *
     * @see \JMS\Serializer\SerializerInterface::deserialize()
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function deserialize($data, $type, $format = null, DeserializationContext $context = null)
    {
        return $this->serializer->deserialize((string) $data, $type, $format, $context);
    }

    private static $annotationRegistryReady = false;

    private static function configureAnnotationRegistry()
    {
        if (self::$annotationRegistryReady) {
            return;
        }

        try {
            $reflectionClass = new \ReflectionClass(AnnotationRegistry::class);
            $reflectionProperty = $reflectionClass->getProperty('loaders');
            $reflectionProperty->setAccessible(true);
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $unused_exception) {
            // Свойство недоступно, или ещё что. Больше не пытаемся.
            self::$annotationRegistryReady = true;

            return;
        }
        // @codeCoverageIgnoreEnd

        // Настройку делаем только если её не сделали за нас.
        if ([] === $reflectionProperty->getValue()) {
            /** @phan-suppress-next-line PhanDeprecatedFunction */
            AnnotationRegistry::registerLoader('class_exists');
        }

        self::$annotationRegistryReady = true;
    }
}