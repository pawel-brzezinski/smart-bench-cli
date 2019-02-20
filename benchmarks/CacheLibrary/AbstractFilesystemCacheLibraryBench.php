<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Benchmark\CacheLibrary;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractFilesystemCacheLibraryBench extends AbstractCacheLibraryBench
{
    const CACHE_DIR = __DIR__.'/../../var/cache';

    /**
     * Flush Redis database.
     */
    public static function flushFilesystem(): void
    {
        $filesystem = new Filesystem();

        try {
            $filesystem->remove(self::CACHE_DIR);
        } catch (IOException $exception) {

        }
    }
}
