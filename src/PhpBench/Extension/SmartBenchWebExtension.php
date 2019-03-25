<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\PhpBench\Extension;

use GuzzleHttp\Client;
use PB\Cli\SmartBench\PhpBench\Extension\Driver\SmartBenchWebDriver;
use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use PhpBench\Serializer\XmlEncoder;
use PhpBench\Storage\StorageRegistry;

/**
 * PHPBench extension for sending benchmark report to SmartBenchWeb website.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SmartBenchWebExtension implements ExtensionInterface
{
    const API_CLIENT_ID = 'smartbenchweb.client';
    const API_REPORT_DRIVER_ID = 'smartbenchweb.driver.api_report';

    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return ['smartbenchweb.api.url' => 'http://example.com', 'smartbenchweb.api.key' => '', 'smartbenchweb.storage_type' => 'xml'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(Container $container)
    {
        $container->register(self::API_CLIENT_ID, function(Container $container) {
            $apiUrl = $container->getParameter('smartbenchweb.api.url');
            $apiKey = $container->getParameter('smartbenchweb.api.key');

            return new Client(['base_uri' => $apiUrl, 'http_errors' => false, 'headers' => [
                'Content-Type' => 'application/xml', 'X-API-KEY' => $apiKey,
            ]]);
        });

        $container->register(self::API_REPORT_DRIVER_ID, function(Container $container) {
            /** @var StorageRegistry $storageRegistry */
            $storageRegistry = $container->get('storage.driver_registry');
            $storage = $storageRegistry->getService($container->getParameter('smartbenchweb.storage_type'));

            $client = $container->get(self::API_CLIENT_ID);

            return new SmartBenchWebDriver($storage, new XmlEncoder(), $client);
        }, ['storage_driver' => ['name' => 'smartbench_web_report']]);
    }
}
