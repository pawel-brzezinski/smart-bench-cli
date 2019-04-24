<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Model;

/**
 * Example model.
 *
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class Book
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var BookCategory
     */
    private $category;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $author;

    /**
     * @var int
     */
    private $year;

    /**
     * @var float
     */
    private $price;

    /**
     * Book constructor.
     *
     * @param BookCategory $category
     * @param string $title
     * @param string $lang
     * @param string $author
     * @param int $year
     * @param float $price
     */
    public function __construct(BookCategory $category, string $title, string $lang, string $author, int $year, float $price)
    {
        $this->category = $category;
        $this->title = $title;
        $this->lang = $lang;
        $this->author = $author;
        $this->year = $year;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return BookCategory
     */
    public function getCategory(): BookCategory
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}
