<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\SymfonyCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractFilesystemCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr6Trait;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit,
    Warmup};
use Symfony\Component\Cache\Adapter\{FilesystemAdapter};

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
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"write", "symfony", "filesystem", "filesystem_write"})
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Warmup(2)
     * @Groups({"read", "symfony", "filesystem", "filesystem_read"})
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
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
}
