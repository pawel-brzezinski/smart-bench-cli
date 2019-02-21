<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\PhpCache;

use Cache\Adapter\Redis\RedisCachePool;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractRedisCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr16Trait;
use PB\Cli\SmartBench\Connection\PhpRedisConnection;
use PhpBench\Benchmark\Metadata\Annotations\{
    AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit,
};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushRedis"})
 */
class PhpCachePredisBench extends AbstractRedisCacheLibraryBench
{
    use Psr16Trait;

    const CACHE_KEY_PREFIX = 'phpcache_predis';

    /**
     * Init cache adapter.
     */
    public function initCache(): void
    {
        $this->cache = self::createAdapter();
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write", "phpcache", "predis", "predis_write"})
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write_tag", "phpcache", "predis", "predis_write_tag"})
     */
    public function benchWriteToTagCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cacheItem->setTags(CacheLibraryConstant::CACHE_TAGS);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"read", "phpcache", "predis", "predis_read"})
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tags", "phpcache", "predis", "predis_invalidate_tags"})
     */
    public function benchInvalidateCacheTag()
    {
        $this->cache->invalidateTags(CacheLibraryConstant::CACHE_TAGS);
    }

    /**
     * Create adapter.
     *
     * @return RedisCachePool
     */
    private static function createAdapter(): RedisCachePool
    {
        return new RedisCachePool(PhpRedisConnection::connect());
    }
}
