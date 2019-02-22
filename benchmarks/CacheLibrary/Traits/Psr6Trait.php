<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary\Traits;

use PB\Cli\SmartBench\Benchmark\CacheLibrary\CacheLibraryConstant;
use PB\Cli\SmartBench\Model\Book;
use Psr\Cache\{CacheItemInterface, CacheItemPoolInterface};

/**
 * PSR-6 trait.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait Psr6Trait
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
            $cache->save($cacheItem);
        }
    }

    /**
     * Init write cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function initWriteCache(): void
    {
        $this->cacheItem = $this->cache->getItem('key_'.uniqid());
        $this->cacheItemValue = self::generateCacheValue();
    }
}
