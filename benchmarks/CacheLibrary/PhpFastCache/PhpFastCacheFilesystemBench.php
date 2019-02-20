<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\PhpFastCache;

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
use Phpfastcache\CacheManager;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Drivers\Files\Config;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushFilesystem"})
 */
class PhpFastCacheFilesystemBench extends AbstractFilesystemCacheLibraryBench
{
    use Psr16Trait;

    const CACHE_KEY_PREFIX = 'phpfastcache-filesystem';

    /**
     * Init cache adapter with usage of \Redis connection.
     */
    public function initCache(): void
    {
        $this->cache = self::createAdapter();
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_write", "phpfastcache", "filesystem"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"cache_write_tag", "phpfastcache", "filesystem"})
     * @Revs(10000)
     * @Iterations(5)
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
     * @Groups({"cache_read", "phpfastcache", "filesystem"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tag", "phpfastcache", "filesystem"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchInvalidateCacheTag()
    {
        $this->cache->deleteItemsByTags(CacheLibraryConstant::CACHE_TAGS);
    }

    /**
     * Create adapter.
     *
     * @return ExtendedCacheItemPoolInterface
     *
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \ReflectionException
     */
    private static function createAdapter(): ExtendedCacheItemPoolInterface
    {
        return CacheManager::getInstance('files', new Config([
            'path' => self::CACHE_DIR,
        ]));
    }
}
