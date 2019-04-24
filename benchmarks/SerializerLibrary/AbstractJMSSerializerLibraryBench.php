<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary;

use Doctrine\Common\Cache\ApcuCache;
use JMS\Serializer\{SerializerBuilder, SerializerInterface};
use Metadata\Cache\DoctrineCacheAdapter;

/**
 * Abstract for JMS serializer library benchmark implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractJMSSerializerLibraryBench extends AbstractSerializerLibraryBench
{
    /**
     * @var SerializerInterface
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
        $cache = new ApcuCache();
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/JMSSerializer/config', 'PB\Cli\SmartBench\Model')
            ->setMetadataCache(new DoctrineCacheAdapter(__CLASS__, $cache))
            ->build()
        ;

        $this->initData($serializer, $format);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    protected function initData($serializer, string $format): void
    {
        if (!$serializer instanceof SerializerInterface) {
            throw new \InvalidArgumentException();
        }

        $this->serializer = $serializer;
        $this->value = $this->generateExampleData();
        $this->serializedValue = $serializer->serialize($this->value, $format);
    }
}
