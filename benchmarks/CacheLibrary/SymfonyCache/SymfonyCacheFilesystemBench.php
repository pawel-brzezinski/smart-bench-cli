<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\SymfonyCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractFilesystemCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr6Trait;
use PhpBench\Benchmark\Metadata\Annotations\{
    AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit
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
    use Psr6Trait;

    const CACHE_KEY_PREFIX = 'symfony_filesystem';

    /**
     * Init cache adapter.
     */
    public function initCache(): void
    {
        $this->cache = self::createAdapter();
    }

    /**
     * Init tag cache adapter.
     */
    public function initTagCache(): void
    {
        $this->cache = self::createTagAdapter();
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write", "symfony", "filesystem", "filesystem_write"})
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initTagCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write_tag", "symfony", "filesystem", "filesystem_write"})
     */
    public function benchWriteToTagCacheWithoutTags()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initTagCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write_tag", "symfony", "filesystem", "filesystem_write_tag"})
     */
    public function benchWriteToTagCacheWithTags()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cacheItem->tag(CacheLibraryConstant::CACHE_TAGS);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"read", "symfony", "filesystem", "filesystem_read"})
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initTagCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"read", "symfony", "filesystem", "filesystem_read"})
     */
    public function benchReadFromTagCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initTagCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tags", "symfony", "filesystem", "filesystem_invalidate_tags"})
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
