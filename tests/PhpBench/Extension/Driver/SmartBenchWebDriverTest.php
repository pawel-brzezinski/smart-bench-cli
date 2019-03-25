<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\PhpBench\Extension\Driver;

use GuzzleHttp\ClientInterface;
use PB\Cli\SmartBench\PhpBench\Extension\Driver\SmartBenchWebDriver;
use PhpBench\Dom\Document;
use PhpBench\Expression\Constraint\Constraint;
use PhpBench\Model\SuiteCollection;
use PhpBench\Serializer\XmlEncoder;
use PhpBench\Storage\{DriverInterface, HistoryIteratorInterface};
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SmartBenchWebDriverTest extends TestCase
{
    /** @var ObjectProphecy|DriverInterface */
    private $storageMock;

    /** @var ObjectProphecy|XmlEncoder */
    private $xmlEncoderMock;

    /** @var ObjectProphecy|ClientInterface */
    private $clientMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->storageMock = $this->prophesize(DriverInterface::class);
        $this->xmlEncoderMock = $this->prophesize(XmlEncoder::class);
        $this->clientMock = $this->prophesize(ClientInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->storageMock = null;
        $this->xmlEncoderMock = null;
        $this->clientMock = null;
    }

    public function storeDataProvider(): array
    {
        $expectedOk = 'SmartBenchWeb: report has been sent successfully!';
        $expectedError = 'SmartBenchWeb: report has not been sent!';

        return [
            'API return 201' => [$expectedOk, 201],
            'API return 400' => [$expectedError, 400],
            'API return 500' => [$expectedError, 500],
        ];
    }

    /**
     * @dataProvider storeDataProvider
     *
     * @param string $expected
     * @param int $statusCode
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testStore(string $expected, int $statusCode)
    {
        // Given
        $suiteCollectionMock = $this->prophesize(SuiteCollection::class);
        /** @var ObjectProphecy|Document $suiteDocumentMock */
        $suiteDocumentMock = $this->prophesize(Document::class);

        // Mock DriverInterface::store()
        $this->storageMock->store($suiteCollectionMock->reveal())->shouldBeCalledTimes(1);
        // End

        // Mock XmlEncoder::encode()
        $this->xmlEncoderMock->encode($suiteCollectionMock->reveal())->shouldBeCalledTimes(1)->willReturn($suiteDocumentMock->reveal());
        // End

        // Mock Document::dump()
        $xml = '<?xml version="1.0"?><phpbench xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="0.15-dev (@git_version@)"/>';
        $suiteDocumentMock->dump()->shouldBeCalledTimes(1)->willReturn($xml);
        // End

        // Mock ClientInterface::request()
        /** @var ObjectProphecy|ResponseInterface $responseMock */
        $responseMock = $this->prophesize(ResponseInterface::class);
        $requestOptions = ['body' => $xml];
        $this->clientMock
            ->request('POST', SmartBenchWebDriver::STORE_API_ENDPOINT, $requestOptions)
            ->shouldBeCalledTimes(1)
            ->willReturn($responseMock->reveal())
        ;
        // End

        // Mock ResponseInterface::getStatusCode()
        $responseMock->getStatusCode()->shouldBeCalledTimes(1)->willReturn($statusCode);
        // End

        // When
        $actual = $this->buildDriver()->store($suiteCollectionMock->reveal());

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testQuery()
    {
        // Given

        // Mock DriverInterface::query()
        $constraintMock = $this->prophesize(Constraint::class);
        $suiteCollectionMock = $this->prophesize(SuiteCollection::class);

        $this->storageMock->query($constraintMock->reveal())->shouldBeCalledTimes(1)->willReturn($suiteCollectionMock->reveal());
        // End

        // When
        $actual = $this->buildDriver()->query($constraintMock->reveal());

        // Then
        $this->assertSame($suiteCollectionMock->reveal(), $actual);
    }

    public function testFetch()
    {
        // Given

        // Mock DriverInterface::fetch()
        $runId = '123-123-123';
        $suiteCollectionMock = $this->prophesize(SuiteCollection::class);

        $this->storageMock->fetch($runId)->shouldBeCalledTimes(1)->willReturn($suiteCollectionMock->reveal());
        // End

        // When
        $actual = $this->buildDriver()->fetch($runId);

        // Then
        $this->assertSame($suiteCollectionMock->reveal(), $actual);
    }

    public function testHas()
    {
        // Given
        $expected = false;

        // Mock DriverInterface::has()
        $runId = '123-123-123';
        $this->storageMock->has($runId)->shouldBeCalledTimes(1)->willReturn($expected);
        // End

        // When
        $actual = $this->buildDriver()->has($runId);

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testDelete()
    {
        // Given

        // Mock DriverInterface::delete()
        $runId = '123-123-123';
        $this->storageMock->delete($runId)->shouldBeCalledTimes(1);
        // End

        // When
        $this->buildDriver()->delete($runId);
    }

    public function testHistory()
    {
        // Given

        // Mock DriverInterface::history()
        $historyMock = $this->prophesize(HistoryIteratorInterface::class);

        $this->storageMock->history()->shouldBeCalledTimes(1)->willReturn($historyMock->reveal());
        // End

        // When
        $actual = $this->buildDriver()->history();

        // Then
        $this->assertSame($historyMock->reveal(), $actual);
    }

    private function buildDriver(): SmartBenchWebDriver
    {
        return new SmartBenchWebDriver(
            $this->storageMock->reveal(),
            $this->xmlEncoderMock->reveal(),
            $this->clientMock->reveal()
        );
    }
}
