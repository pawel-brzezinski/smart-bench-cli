<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Connection;

/**
 * Interface for connection implementation.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
interface ConnectionInterface
{
    /**
     * Return initialized connection.
     *
     * @return mixed
     */
    public static function connect();
}
