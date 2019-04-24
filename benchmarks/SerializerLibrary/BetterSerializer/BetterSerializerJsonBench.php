<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\BetterSerializer;

use BetterSerializer\Common\SerializationType;
use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractBetterSerializerLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class BetterSerializerJsonBench extends AbstractBetterSerializerLibraryBench
{
    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function initSerializer(): void
    {
        $this->doInitSerializer('json');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "better_serializer", "json", "json_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, SerializationType::JSON());
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "better_serializer", "json", "json_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->deserialize($this->serializedValue, Book::class, SerializationType::JSON());
    }
}
