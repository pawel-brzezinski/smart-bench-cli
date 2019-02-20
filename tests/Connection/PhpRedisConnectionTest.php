<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Connection;

use PB\Cli\SmartBench\Connection\PhpRedisConnection;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class PhpRedisConnectionTest extends TestCase
{
    public function testConnect()
    {
        // When
        $actual = PhpRedisConnection::connect();

        // Then
        $this->assertInstanceOf(\Redis::class, $actual);
    }
}
