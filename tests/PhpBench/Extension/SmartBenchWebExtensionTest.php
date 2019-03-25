<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\PhpBench\Extension;

use GuzzleHttp\ClientInterface;
use PB\Cli\SmartBench\PhpBench\Extension\Driver\SmartBenchWebDriver;
use PB\Cli\SmartBench\PhpBench\Extension\SmartBenchWebExtension;
use PhpBench\DependencyInjection\Container;
use PhpBench\Storage\Driver\Xml\XmlDriver;
use PhpBench\Storage\StorageRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SmartBenchWebExtensionTest extends TestCase
{
    public function testGetDefaultConfig()
    {
        // Given
        $expected = [
            'smartbenchweb.api.url' => 'http://example.com',
            'smartbenchweb.api.key' => '',
            'smartbenchweb.storage_type' => 'xml',
        ];

        // When
        $actual = $this->buildExtension()->getDefaultConfig();

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testLoad()
    {
        // Given
        $apiUrl = 'http://example.com';
        $apiKey = 'fooBar';
        $storageType = 'xml';

        $storageRegistryMock = $this->prophesize(StorageRegistry::class);
        $xmlDriverMock = $this->prophesize(XmlDriver::class);

        $container = new Container();
        $container->setParameter('smartbenchweb.api.url', $apiUrl);
        $container->setParameter('smartbenchweb.api.key', $apiKey);
        $container->setParameter('smartbenchweb.storage_type', $storageType);

        $container->set('storage.driver_registry', $storageRegistryMock->reveal());

        // Mock StorageRegistry::getService()
        $storageRegistryMock->getService($storageType)->shouldBeCalledTimes(1)->willReturn($xmlDriverMock->reveal());
        // End

        // When
        $this->buildExtension()->load($container);
        /** @var ClientInterface $actualClient */
        $actualClient = $container->get(SmartBenchWebExtension::API_CLIENT_ID);
        $actualApiReport = $container->get(SmartBenchWebExtension::API_REPORT_DRIVER_ID);

        // Then
        $this->assertInstanceOf(ClientInterface::class, $actualClient);
        $this->assertSame($apiUrl, $actualClient->getConfig('base_uri')->__toString());
        $this->assertFalse($actualClient->getConfig('http_errors'));
        $this->assertSame('application/xml', $actualClient->getConfig('headers')['Content-Type']);
        $this->assertSame($apiKey, $actualClient->getConfig('headers')['X-API-KEY']);

        $this->assertInstanceOf(SmartBenchWebDriver::class, $actualApiReport);
    }

    private function buildExtension(): SmartBenchWebExtension
    {
        return new SmartBenchWebExtension();
    }
}
