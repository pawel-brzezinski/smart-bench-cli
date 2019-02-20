<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use PB\Cli\SmartBench\Connection\{PhpRedisConnection, PredisConnection};
use PB\Cli\SmartBench\Model\Book;
use PhpBench\Benchmark\Metadata\Annotations\{AfterClassMethods,
    BeforeMethods,
    Groups,
    Iterations,
    OutputTimeUnit,
    Revs};
use Symfony\Component\Cache\Adapter\{AdapterInterface, RedisAdapter, TagAwareAdapterInterface};
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @AfterClassMethods({"flushRedis"})
 */
class SymfonyCacheComponentBench extends AbstractCacheLibraryBench
{
    /**
     * @var AdapterInterface|TagAwareAdapterInterface
     */
    private $cache;

    /**
     * @var ItemInterface
     */
    private $cacheItem;

    /**
     * @var Book
     */
    private $cacheItemValue;

    /**
     * Init cache adapter with usage of \Redis connection.
     */
    public function initPhpRedisCache(): void
    {
        $this->cache = new RedisAdapter(PhpRedisConnection::connect());
    }

    /**
     * Init cache adapter with usage of \Predis connection.
     */
    public function initPredisCache(): void
    {
        $this->cache = new RedisAdapter(PredisConnection::connect());
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
     * @Groups({"symfony", "phpredis"})
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
     * @Groups({"symfony", "predis"})
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchPredisAdapter()
    {
        $this->cacheItem->set($this->cacheItemValue);
        $this->cache->save($this->cacheItem);
    }
}
