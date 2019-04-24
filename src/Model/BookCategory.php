<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Model;

/**
 * Example book category model.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class BookCategory
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * BookCategory constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
