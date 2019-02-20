<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Connection;

use PB\Cli\SmartBench\Config\AppConfig;

/**
 * PhpRedis connection.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class PhpRedisConnection implements ConnectionInterface
{
    /**
     * {@inheritdoc}
     */
    public static function connect(): \Redis
    {
        $config = AppConfig::getInstance()->getRedisConfig();

        $redis = new \Redis();
        $redis->connect($config['host'], $config['port'], $config['timeout']);
        $redis->select($config['database']);

        return $redis;
    }
}
