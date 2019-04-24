<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary;

use BetterSerializer\{Builder, Common\SerializationType, Serializer};

/**
 * Abstract for Better Serializer library benchmark implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractBetterSerializerLibraryBench extends AbstractSerializerLibraryBench
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Init serializer.
     *
     * @param string $format
     *
     * @throws \ReflectionException
     */
    protected function doInitSerializer(string $format): void
    {
        $builder = new Builder();
        $builder->enableApcuCache();

        $serializer = $builder->createSerializer();

        $this->initData($serializer, $format);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    protected function initData($serializer, string $format): void
    {
        if (!$serializer instanceof Serializer) {
            throw new \InvalidArgumentException();
        }

        switch ($format) {
            case 'json':
                $format = SerializationType::JSON();
                break;
        }

        $this->serializer = $serializer;
        $this->value = $this->generateExampleData();

        $this->serializedValue = $serializer->serialize($this->value, $format);
    }
}
