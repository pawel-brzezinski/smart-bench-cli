<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\SerializardReflection;

use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractSerializardLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SerializardReflectionXMLBench extends AbstractSerializardLibraryBench
{
    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function initSerializer(): void
    {
        $this->doInitSerializer(self::REFLECTION_TYPE, 'xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "serializard_reflection", "xml", "xml_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, 'xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "serializard_reflection", "xml", "xml_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->unserialize($this->serializedValue, Book::class, 'xml');
    }
}
