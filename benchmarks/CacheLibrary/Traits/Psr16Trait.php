<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Model\Book;
use Psr\Cache\{CacheItemInterface, CacheItemPoolInterface};

/**
 * PSR-16 trait.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait Psr16Trait
{
    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    /**
     * @var CacheItemInterface
     */
    protected $cacheItem;

    /**
     * @var Book
     */
    protected $cacheItemValue;

    /**
     * Create fake data in cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public static function initFakeData()
    {
        /** @var CacheItemPoolInterface $cache */
        $cache = self::createAdapter();
        $value = self::generateCacheValue();

        for ($i = 1; $i <= CacheLibraryConstant::ITEMS_COUNT; $i++) {
            $cacheKey = self::generateCacheKey((string) $i, self::CACHE_KEY_PREFIX);

            $cacheItem = $cache->getItem($cacheKey);
            $cacheItem->set($value);
            $cache->saveDeferred($cacheItem);
        }

        $cache->commit();
    }

    /**
     * Init write cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function initWriteCache(): void
    {
        $this->cacheItem = $this->cache->getItem('key-'.uniqid());
        $this->cacheItemValue = self::generateCacheValue();
    }
}
