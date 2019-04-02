<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\ScrapbookTaggable;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MatthiasMullie\Scrapbook\Adapters\Flysystem;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractFilesystemCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr6Trait;
use PB\Extension\Scrapbook\Tag\Psr6\TaggablePool;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit,
    Warmup};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushFilesystem"})
 */
class ScrapbookTaggableFilesystemBench extends AbstractFilesystemCacheLibraryBench
{
    use Psr6Trait;

    const CACHE_KEY_PREFIX = 'scrapbook_taggable_filesystem';

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
     * @Warmup(2)
     * @Groups({"write", "scrapbook_taggable", "filesystem", "filesystem_write"})
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"write_tag", "scrapbook_taggable", "filesystem", "filesystem_write_tag"})
     */
    public function benchWriteToTagCacheWithTags()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cacheItem->setTags(CacheLibraryConstant::CACHE_TAGS);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"read", "scrapbook_taggable", "filesystem", "filesystem_read"})
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"invalidate_tags", "scrapbook_taggable", "filesystem", "filesystem_invalidate_tags"})
     */
    public function benchInvalidateCacheTag()
    {
        $this->cache->invalidateTags(CacheLibraryConstant::CACHE_TAGS);
    }

    /**
     * Create adapter.
     *
     * @return TaggablePool
     */
    private static function createAdapter(): TaggablePool
    {
        $adapter = new Local(self::CACHE_DIR);
        $filesystem = new Filesystem($adapter);

        $cache = new Flysystem($filesystem);

        return new TaggablePool($cache);
    }
}
