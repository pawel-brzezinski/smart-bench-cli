<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Config;

use PB\Cli\SmartBench\Config\AppConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class AppConfigTest extends TestCase
{
    public function testGetConfig()
    {
        // Given
        $expected = $this->getConfig();

        // When
        $actual = AppConfig::getInstance()->getConfig();

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testGetRedisConfig()
    {
        // Given
        $expected = $this->getConfig()['redis'];

        // When
        $actual = AppConfig::getInstance()->getRedisConfig();

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testGetMemcachedConfig()
    {
        // Given
        $expected = $this->getConfig()['memcached'];

        // When
        $actual = AppConfig::getInstance()->getMemcachedConfig();

        // Then
        $this->assertSame($expected, $actual);
    }

    private function getConfig(): array
    {
        // Given
        return Yaml::parseFile(__DIR__.'/../../app/config.yaml');
    }
}
