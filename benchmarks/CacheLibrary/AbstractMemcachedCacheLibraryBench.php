<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Connection\MemcachedConnection;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractMemcachedCacheLibraryBench extends AbstractCacheLibraryBench
{
    /**
     * Flush Memcached.
     */
    public static function flushMemcached(): void
    {
        $memcached = MemcachedConnection::connect();
        $memcached->flush();
    }
}
