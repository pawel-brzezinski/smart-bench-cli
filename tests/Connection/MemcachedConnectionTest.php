<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Connection;

use PB\Cli\SmartBench\Connection\MemcachedConnection;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class MemcachedConnectionTest extends TestCase
{
    public function testConnect()
    {
        // When
        $actual = MemcachedConnection::connect();

        // Then
        $this->assertInstanceOf(\Memcached::class, $actual);
    }
}
