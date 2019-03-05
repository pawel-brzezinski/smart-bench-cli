<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\PhpFastCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractMemcachedCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr6Trait;
use PB\Cli\SmartBench\Config\AppConfig;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit,
    Sleep,
    Warmup};
use Phpfastcache\CacheManager;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Drivers\Memcached\Config;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushMemcached"})
 */
class PhpFastCacheMemcachedBench extends AbstractMemcachedCacheLibraryBench
{
    use Psr6Trait;

    const CACHE_KEY_PREFIX = 'phpfastcache_memcached';

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
     * @Groups({"write", "phpfastcache", "memcached", "memcached_write"})
     * @Sleep(1000000)
     * @Warmup(2)
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write_tag", "phpfastcache", "memcached", "memcached_write_tag"})
     * @Sleep(1000000)
     * @Warmup(2)
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
     * @Groups({"read", "phpfastcache", "memcached", "memcached_read"})
     * @Sleep(1000000)
     * @Warmup(2)
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tags", "phpfastcache", "memcached", "memcached_invalidate_tags"})
     * @Sleep(1000000)
     * @Warmup(2)
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
        $config = AppConfig::getInstance()->getMemcachedConfig();

        return CacheManager::getInstance(' memcached', new Config([
            'host' => $config['host'],
            'port' => $config['port'],
        ]));
    }
}
