<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Connection;

use PB\Cli\SmartBench\Config\AppConfig;
use Predis\Client;

/**
 * Predis connection.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class PredisConnection implements ConnectionInterface
{
    /**
     * {@inheritdoc}
     */
    public static function connect(): Client
    {
        $config = AppConfig::getInstance()->getRedisConfig();

        $predis = new Client([
            'scheme' => 'tcp',
            'host'   => $config['host'],
            'port'   => $config['port'],
            'timeout' => $config['timeout'],
            'database' => $config['database'],
        ]);

        return $predis;
    }
}
