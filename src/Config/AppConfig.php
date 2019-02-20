<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Application configuration singleton.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class AppConfig
{
    const CONFIG_PATH =  __DIR__.'/../../app/config.yaml';

    /**
     * @var AppConfig
     */
    private static $instance;

    /**
     * @var array
     */
    private $config;

    /**
     * AppConfig constructor.
     */
    private function __construct()
    {
        $this->config = Yaml::parseFile(self::CONFIG_PATH);
    }

    /**
     * Get singleton config.
     *
     * @return AppConfig
     */
    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get Redis config.
     *
     * @return array
     */
    public function getRedisConfig(): array
    {
        return $this->config['redis'];
    }

    /**
     * Get Memcache config.
     *
     * @return array
     */
    public function getMemcacheConfig(): array
    {
        return $this->config['memcache'];
    }
}
