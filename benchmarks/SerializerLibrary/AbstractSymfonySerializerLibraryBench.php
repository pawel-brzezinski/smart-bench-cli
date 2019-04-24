<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary;

use Symfony\Component\Serializer\Encoder\{JsonEncoder, XmlEncoder};
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\{Serializer, SerializerInterface};

/**
 * Abstract for Symfony serializer library benchmark implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractSymfonySerializerLibraryBench extends AbstractSerializerLibraryBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Init serializer.
     *
     * @param NormalizerInterface $normalizer
     * @param string $format
     *
     * @throws \ReflectionException
     */
    protected function doInitSerializer(NormalizerInterface $normalizer, string $format): void
    {
        $encoder = '';

        switch ($format) {
            case 'json':
                $encoder = new JsonEncoder();
                break;
            case 'xml':
                $encoder = new XmlEncoder();
                break;
        }

        $encoders = [$encoder];
        $normalizers = [$normalizer];
        $serializer = new Serializer($normalizers, $encoders);

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
