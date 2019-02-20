<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Connection\PhpRedisConnection;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractRedisCacheLibraryBench extends AbstractCacheLibraryBench
{
    /**
     * Flush Redis database.
     */
    public static function flushRedis(): void
    {
        $phpRedis = PhpRedisConnection::connect();
        $phpRedis->flushDB();
    }
}
