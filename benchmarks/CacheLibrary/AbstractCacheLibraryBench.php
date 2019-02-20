<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Connection\PhpRedisConnection;
use PB\Cli\SmartBench\Model\Book;
use PB\Cli\SmartBench\Tests\Fake\Model\GenerateModelTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractCacheLibraryBench
{
    use GenerateModelTrait;

    /**
     * Flush Redis database.
     */
    public static function flushRedis(): void
    {
        $phpRedis = PhpRedisConnection::connect();
        $phpRedis->flushDB();
    }

    /**
     * Generate cache value.
     *
     * @return Book
     *
     * @throws ReflectionException
     */
    protected function generateCacheValue(): Book
    {
        $bookCat = $this->generateBookCategoryModel(1, 'Book category name');
        $book = $this->generateBook(
            1, $bookCat, 'Book title', 'en', 'Foo Barek', 2019, 39.99
        );

        return $book;
    }
}
