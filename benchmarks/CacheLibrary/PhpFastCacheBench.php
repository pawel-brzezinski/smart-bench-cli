<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Config\AppConfig;
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeMethods,
    Groups,
    Iterations,
    OutputTimeUnit,
    Revs};
use Phpfastcache\CacheManager;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Drivers\Predis\Config as PredisConfig;
use Phpfastcache\Drivers\Redis\Config as RedisConfig;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @AfterClassMethods({"flushRedis"})
 */
class PhpFastCacheBench extends AbstractCacheLibraryBench
{
    /**
     * @var ExtendedCacheItemPoolInterface
     */
    private $cache;

    /**
     * @var ExtendedCacheItemInterface
     */
    private $cacheItem;

    /**
     * @var Book
     */
    private $cacheItemValue;

    /**
     * Init cache adapter with usage of \Redis connection.
     *
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \ReflectionException
     */
    public function initPhpRedisCache(): void
    {
        $config = AppConfig::getInstance()->getRedisConfig();

        $this->cache = CacheManager::getInstance('redis', new RedisConfig([
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'timeout' => $config['timeout'],
        ]));

    }

    /**
     * Init cache adapter with usage of \Predis connection.
     *
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \ReflectionException
     */
    public function initPredisCache(): void
    {
        $config = AppConfig::getInstance()->getRedisConfig();

        $this->cache = CacheManager::getInstance('predis', new PredisConfig([
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'timeout' => $config['timeout'],
        ]));
    }

    /**
     * Init cache item which is not hit.
     *
     * @throws ReflectionException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function initNotHitCacheItem(): void
    {
        $this->cacheItem = $this->cache->getItem('key-'.uniqid());
        $this->cacheItemValue = $this->generateCacheValue();
    }

    /**
     * @BeforeMethods({"initPhpRedisCache", "initNotHitCacheItem"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"phpfastcache", "phpredis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchPhpRedisAdapter()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }

    /**
     * @BeforeMethods({"initPredisCache", "initNotHitCacheItem"})
     * @OutputTimeUnit("milliseconds", precision=3)
     * @Groups({"phpfastcache", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchPredisAdapter()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }
}
