<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Connection;

use PB\Cli\SmartBench\Config\AppConfig;

/**
 * Memcached connection.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class MemcachedConnection implements ConnectionInterface
{
    /**
     * {@inheritdoc}
     */
    public static function connect(): \Memcached
    {
        $config = AppConfig::getInstance()->getRedisConfig();

        $memcached = new \Memcached();
        $memcached->addServer($config['host'], $config['port']);

        return $memcached;
    }
}
