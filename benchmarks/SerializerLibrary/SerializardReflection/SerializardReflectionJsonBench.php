<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\SerializardReflection;

use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractSerializardLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SerializardReflectionJsonBench extends AbstractSerializardLibraryBench
{
    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function initSerializer(): void
    {
        $this->doInitSerializer(self::REFLECTION_TYPE, 'json');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "serializard_reflection", "json", "json_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, 'json');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "serializard_reflection", "json", "json_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->unserialize($this->serializedValue, Book::class, 'json');
    }
}
