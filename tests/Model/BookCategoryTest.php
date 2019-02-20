<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Model;

use PB\Cli\SmartBench\Model\BookCategory;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class BookCategoryTest extends TestCase
{
    const DEFAULT_ID = 1;
    const DEFAULT_NAME = 'Some book category';

    public function testGetId()
    {
        // When
        $actual = $this->buildModel()->getId();

        // Then
        $this->assertSame(self::DEFAULT_ID, $actual);
    }

    public function testGetName()
    {
        // When
        $actual = $this->buildModel()->getName();

        // Then
        $this->assertSame(self::DEFAULT_NAME, $actual);
    }

    private function buildModel(): BookCategory
    {
        $model = new BookCategory(self::DEFAULT_NAME);

        $ref = new \ReflectionClass($model);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($model, self::DEFAULT_ID);

        return $model;
    }
}
