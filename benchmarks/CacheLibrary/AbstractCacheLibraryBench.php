<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Model\{Book, BookCategory};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractCacheLibraryBench
{
    /**
     * Generate cache value.
     *
     * @return Book
     *
     * @throws \ReflectionException
     */
    protected static function generateCacheValue(): Book
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
        $bookProp->setValue($bookRef, 1);

        return $book;
    }

    /**
     * Generate cache key.
     *
     * @param string $key
     * @param string $prefix
     *
     * @return string
     */
    protected static function generateCacheKey(string $key, string $prefix = 'default')
    {
        return $prefix.'-key-'.$key;
    }
}
