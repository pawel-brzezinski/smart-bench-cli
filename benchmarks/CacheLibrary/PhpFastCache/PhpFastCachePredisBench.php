<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\PhpFastCache;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\AbstractRedisCacheLibraryBench;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits\Psr16Trait;
use PB\Cli\SmartBench\Config\AppConfig;
use PhpBench\Benchmark\Metadata\Annotations\{
    AfterClassMethods,
    BeforeClassMethods,
    BeforeMethods,
    Groups,
    OutputTimeUnit
};
use Phpfastcache\CacheManager;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Drivers\Predis\Config;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @BeforeClassMethods({"initFakeData"})
 * @AfterClassMethods({"flushRedis"})
 */
class PhpFastCachePredisBench extends AbstractRedisCacheLibraryBench
{
    use Psr16Trait;

    const CACHE_KEY_PREFIX = 'phpfastcache_predis';

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
     * @Groups({"write", "phpfastcache", "predis", "predis_write"})
     */
    public function benchWriteToCache()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initCache", "initWriteCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"write_tag", "phpfastcache", "predis", "predis_write_tag"})
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
     * @Groups({"read", "phpfastcache", "predis", "predis_read"})
     */
    public function benchReadFromCache()
    {
        $cacheKey = self::generateCacheKey((string) rand(1, CacheLibraryConstant::ITEMS_COUNT), self::CACHE_KEY_PREFIX);
        $this->cache->getItem($cacheKey);
    }

    /**
     * @BeforeMethods({"initCache"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"invalidate_tags", "phpfastcache", "predis", "predis_invalidate_tags"})
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
        $config = AppConfig::getInstance()->getRedisConfig();

        return CacheManager::getInstance('predis', new Config([
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'timeout' => $config['timeout'],
        ]));
    }
}
