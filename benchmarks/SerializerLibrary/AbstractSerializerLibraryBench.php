<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\SerializerLibrary;

use PB\Cli\SmartBench\Model\{Book, BookCategory};

/**
 * Abstract for serializer library benchmark implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractSerializerLibraryBench
{
    /**
     * @var mixed
     */
    protected $serializer;

    /**
     * @var Book
     */
    protected $value;

    /**
     * @var string
     */
    protected $serializedValue;

    /**
     * Init serializer.
     */
    abstract public function initSerializer(): void;

    /**
     * Init serializer data.
     *
     * @param mixed $serializer
     * @param string $format
     */
    abstract protected function initData($serializer, string $format): void;

    /**
     * Generate example data object.
     *
     * @return Book
     *
     * @throws \ReflectionException
     */
    protected function generateExampleData(): Book
    {
        $bookCat = new BookCategory('Book category name');
        $bookCatRef = new \ReflectionClass($bookCat);
        $bookCatProp = $bookCatRef->getProperty('id');
        $bookCatProp->setAccessible(true);
        $bookCatProp->setValue($bookCat, 1);

        $book = new Book($bookCat, 'Book title', 'en', 'Foo Barek', 2019, 39.99);
        $bookRef = new \ReflectionClass($book);
        $bookProp = $bookRef->getProperty('id');
        $bookProp->setAccessible(true);
        $bookProp->setValue($book, 1);

        return $book;
    }
}
