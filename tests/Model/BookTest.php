<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Model;

use PB\Cli\SmartBench\Model\{Book, BookCategory};
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class BookTest extends TestCase
{
    const DEFAULT_ID = 1;
    const DEFAULT_TITLE = 'Example book title';
    const DEFAULT_LANG = 'en';
    const DEFAULT_AUTHOR = 'Foo Barek';
    const DEFAULT_YEAR = 2019;
    const DEFAULT_PRICE = 38.88;

    /** @var BookCategory */
    private $defaultCategory;

    protected function setUp()
    {
        $defCategory = new BookCategory('Some book category');
        $ref = new \ReflectionClass($defCategory);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($defCategory, 1);

        $this->defaultCategory = $defCategory;
    }

    protected function tearDown()
    {
        $this->defaultCategory = null;
    }

    public function testGetId()
    {
        // When
        $actual = $this->buildModel()->getId();

        // Then
        $this->assertSame(self::DEFAULT_ID, $actual);
    }

    public function testGetCategory()
    {
        // When
        $actual = $this->buildModel()->getCategory();

        // Then
        $this->assertSame($this->defaultCategory, $actual);
    }

    public function testGetTitle()
    {
        // When
        $actual = $this->buildModel()->getTitle();

        // Then
        $this->assertSame(self::DEFAULT_TITLE, $actual);
    }

    public function testGetLang()
    {
        // When
        $actual = $this->buildModel()->getLang();

        // Then
        $this->assertSame(self::DEFAULT_LANG, $actual);
    }

    public function testGetAuthor()
    {
        // When
        $actual = $this->buildModel()->getAuthor();

        // Then
        $this->assertSame(self::DEFAULT_AUTHOR, $actual);
    }

    public function testGetYear()
    {
        // When
        $actual = $this->buildModel()->getYear();

        // Then
        $this->assertSame(self::DEFAULT_YEAR, $actual);
    }

    public function testGetPrice()
    {
        // When
        $actual = $this->buildModel()->getPrice();

        // Then
        $this->assertSame(self::DEFAULT_PRICE, $actual);
    }

    private function buildModel(): Book
    {
        $model = new Book(
            $this->defaultCategory,
            self::DEFAULT_TITLE,
            self::DEFAULT_LANG,
            self::DEFAULT_AUTHOR,
            self::DEFAULT_YEAR,
            self::DEFAULT_PRICE
        );

        $ref = new \ReflectionClass($model);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($model, self::DEFAULT_ID);

        return $model;
    }
}
