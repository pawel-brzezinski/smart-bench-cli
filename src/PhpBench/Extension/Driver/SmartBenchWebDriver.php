<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\PhpBench\Extension\Driver;

use GuzzleHttp\ClientInterface;
use PhpBench\Expression\Constraint\Constraint;
use PhpBench\Model\SuiteCollection;
use PhpBench\Serializer\XmlEncoder;
use PhpBench\Storage\DriverInterface;

/**
 * PHPBench extension for sending benchmark report to SmartBenchWeb website.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SmartBenchWebDriver implements DriverInterface
{
    const STORE_API_ENDPOINT = '/api/v1/suite/report';

    /**
     * @var DriverInterface
     */
    private $storage;

    /**
     * @var XmlEncoder
     */
    private $xmlEncoder;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * SmartBenchWebDriver constructor.
     *
     * @param DriverInterface $storage
     * @param XmlEncoder $xmlEncoder
     * @param ClientInterface $client
     */
    public function __construct(DriverInterface $storage, XmlEncoder $xmlEncoder, ClientInterface $client)
    {
        $this->storage = $storage;
        $this->xmlEncoder = $xmlEncoder;
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function store(SuiteCollection $collection)
    {
        $this->storage->store($collection);

        $suiteDocument = $this->xmlEncoder->encode($collection);
        $response = $this->client->request('POST', self::STORE_API_ENDPOINT, ['body' => $suiteDocument->dump()]);

        if (201 === $response->getStatusCode()) {
            return 'SmartBenchWeb: report has been sent successfully!';
        }

        return 'SmartBenchWeb: report has not been sent!';
    }

    /**
     * {@inheritdoc}
     */
    public function query(Constraint $constraint)
    {
        return $this->storage->query($constraint);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($runId)
    {
        return $this->storage->fetch($runId);
    }

    /**
     * {@inheritdoc}
     */
    public function has($runId)
    {
        return $this->storage->has($runId);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($runId)
    {
        $this->storage->delete($runId);
    }

    /**
     * {@inheritdoc}
     */
    public function history()
    {
        return $this->storage->history();
    }
}
