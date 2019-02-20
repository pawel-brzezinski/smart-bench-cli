<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\SymfonyCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractRedisCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr16Trait;
use PB\Cli\SmartBench\Connection\PredisConnection;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    Iterations,
    OutputTimeUnit,
    Revs};
use Symfony\Component\Cache\Adapter\{RedisAdapter, TagAwareAdapter};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initRedisData"})
 * @AfterClassMethods({"flushRedis"})
 */
class SymfonyCachePredisBench extends AbstractRedisCacheLibraryBench
{
    use Psr16Trait;

    const CACHE_KEY_PREFIX = 'symfony-predis';

    /**
     * Init cache adapter with usage of \Redis connection.
     */
    public function initCache(): void
    {
        $this->cache = self::createAdapter();
    }

    /**
     * Init tag cache adapter with usage of \Redis connection.
     */
    public function initTagCache(): void
    {
        $this->cache = self::createTagAdapter();
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_write", "symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initTagCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_write_tag", "symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchWriteToTagCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cacheItem->tag(CacheLibraryConstant::CACHE_TAGS);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_read", "symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initTagCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_read", "symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchReadFromTagCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initTagCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tag", "symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchInvalidateCacheTag()
    {
        $this->cache->invalidateTags(CacheLibraryConstant::CACHE_TAGS);
    }

    /**
     * Create adapter.
     *
     * @return RedisAdapter
     */
    private static function createAdapter(): RedisAdapter
    {
        return new RedisAdapter(PredisConnection::connect());
    }

    /**
     * Create tag adapter.
     *
     * @return TagAwareAdapter
     */
    private static function createTagAdapter(): TagAwareAdapter
    {
        return new TagAwareAdapter(self::createAdapter());
    }
}
