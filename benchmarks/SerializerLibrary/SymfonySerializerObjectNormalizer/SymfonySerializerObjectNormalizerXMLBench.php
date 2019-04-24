<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary\SymfonySerializerObjectNormalizer;

use PB\Cli\SmartBench\Benchmark\SerializerLibrary\AbstractSymfonySerializerLibraryBench;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{BeforeMethods, Groups, OutputTimeUnit, Warmup};
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SymfonySerializerObjectNormalizerXMLBench extends AbstractSymfonySerializerLibraryBench
{
    /**
     * Init serializer.
     */
    public function initSerializer(): void
    {
        $normalizer = new ObjectNormalizer();

        $this->doInitSerializer($normalizer, 'xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"serialize", "symfony_object", "xml", "xml_serialize"})
     */
    public function benchSerialize()
    {
        $this->serializer->serialize($this->value, 'xml');
    }

    /**
     * @BeforeMethods({"initSerializer"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"deserialize", "symfony_object", "xml", "xml_deserialize"})
     */
    public function benchDeserialize()
    {
        $this->serializer->deserialize($this->serializedValue, Book::class, 'xml');
    }
}
