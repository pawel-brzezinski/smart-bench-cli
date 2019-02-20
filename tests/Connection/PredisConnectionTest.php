<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Connection;

use PB\Cli\SmartBench\Connection\PredisConnection;
use PHPUnit\Framework\TestCase;
use Predis\Client;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class PredisConnectionTest extends TestCase
{
    public function testConnect()
    {
        // When
        $actual = PredisConnection::connect();

        // Then
        $this->assertInstanceOf(Client::class, $actual);
    }
}
