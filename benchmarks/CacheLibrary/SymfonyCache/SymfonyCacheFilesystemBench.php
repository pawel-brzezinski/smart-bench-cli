<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\SymfonyCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractFilesystemCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr16Trait;
use PhpBench\Benchmark\Metadata\Annotations\{
    AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    Iterations,
    OutputTimeUnit,
    Revs
};
use Symfony\Component\Cache\Adapter\{FilesystemAdapter, TagAwareAdapter};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushFilesystem"})
 */
class SymfonyCacheFilesystemBench extends AbstractFilesystemCacheLibraryBench
{
    use Psr16Trait;

    const CACHE_KEY_PREFIX = 'symfony-filesystem';

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
     * @Groups({"cache_write", "symfony", "filesystem"})
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
     * @Groups({"cache_write_tag", "symfony", "filesystem"})
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
     * @Groups({"cache_read", "symfony", "filesystem"})
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
     * @Groups({"cache_read", "symfony", "filesystem"})
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
     * @Groups({"invalidate_tag", "symfony", "filesystem"})
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
     * @return FilesystemAdapter
     */
    private static function createAdapter(): FilesystemAdapter
    {
        return new FilesystemAdapter(self::CACHE_KEY_PREFIX, 0,self::CACHE_DIR);
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
