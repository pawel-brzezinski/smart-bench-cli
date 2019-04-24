<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\SymfonySerializerGetSetNormalizer;

use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractSymfonySerializerLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SymfonySerializerGetSetNormalizerJsonBench extends AbstractSymfonySerializerLibraryBench
{
    /**
     * Init serializer.
     */
    public function initSerializer(): void
    {
        $normalizer = new GetSetMethodNormalizer();

        $this->doInitSerializer($normalizer, 'json');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "symfony_getset", "json", "json_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, 'json');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "symfony_getset", "json", "json_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->deserialize($this->serializedValue, Book::class, 'json');
    }
}
