<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\JMSSerializer;

use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractJMSSerializerLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class JMSSerializerXMLBench extends AbstractJMSSerializerLibraryBench
{
    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function initSerializer(): void
    {
        $this->doInitSerializer('xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "jms", "xml", "xml_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, 'xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "jms", "xml", "xml_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->deserialize($this->serializedValue, Book::class, 'xml');
    }
}
