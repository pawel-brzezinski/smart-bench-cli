<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary;

use PB\Cli\SmartBench\Model\{Book, BookCategory};
use PB\Tests\Extension\Scrapbook\Tag\Library\Reflection;
use Thunder\Serializard\Format\{JsonFormat, XmlFormat};
use Thunder\Serializard\FormatContainer\FormatContainer;
use Thunder\Serializard\HydratorContainer\FallbackHydratorContainer;
use Thunder\Serializard\Normalizer\ReflectionNormalizer;
use Thunder\Serializard\NormalizerContainer\FallbackNormalizerContainer;
use Thunder\Serializard\Serializard;
use Thunder\Serializard\Utility\RootElementProviderUtility;

/**
 * Abstract for Serializard library benchmark implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractSerializardLibraryBench extends AbstractSerializerLibraryBench
{
    const REFLECTION_TYPE = 'reflection';
    const CLOSURE_TYPE = 'closure';

    /**
     * @var Serializard
     */
    protected $serializer;

    /**
     * Init serializer.
     *
     * @param string $type
     * @param string $format
     *
     * @throws \ReflectionException
     */
    protected function doInitSerializer(string $type, string $format): void
    {
        $formats = new FormatContainer();

        switch ($format) {
            case 'json':
                $formats->add('json', new JsonFormat());
                break;
            case 'xml':
                $formats->add('xml', new XmlFormat(new RootElementProviderUtility([
                    Book::class => 'book',
                    BookCategory::class => 'category'
                ])));
                break;
        }

        $hydrators = new FallbackHydratorContainer();
        $hydrators->add(Book::class, function(array $data) {
            $category = new BookCategory($data['category']['name']);
            Reflection::setPropertyValue($category, 'id', $data['category']['id']);

            $book = new Book($category, $data['title'], $data['lang'], $data['author'], (int) $data['year'], (float) $data['price']);
            Reflection::setPropertyValue($book, 'id', $data['id']);

            return $book;
        });

        $normalizers = new FallbackNormalizerContainer();

        if (self::REFLECTION_TYPE === $type) {
            $normalizers->add(Book::class, new ReflectionNormalizer());
            $normalizers->add(BookCategory::class, new ReflectionNormalizer());
        } elseif (self::CLOSURE_TYPE === $type) {
            $normalizers->add(Book::class, function(Book $book) {
                return [
                    'id' => $book->getId(),
                    'category' => $book->getCategory(),
                    'title' => $book->getTitle(),
                    'lang' => $book->getLang(),
                    'author' => $book->getAuthor(),
                    'year' => $book->getYear(),
                    'price' => $book->getPrice(),
                ];
            });
            $normalizers->add(BookCategory::class, function(BookCategory $category) {
                return [
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                ];
            });
        }

        $serializer = new Serializard($formats, $normalizers, $hydrators);

        $this->initData($serializer, $format);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    protected function initData($serializer, string $format): void
    {
        if (!$serializer instanceof Serializard) {
            throw new \InvalidArgumentException();
        }

        $this->serializer = $serializer;
        $this->value = $this->generateExampleData();

        $this->serializedValue = $serializer->serialize($this->value, $format);
    }
}
