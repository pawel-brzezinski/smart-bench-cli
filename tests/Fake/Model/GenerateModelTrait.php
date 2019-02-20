<?php

declare(strict_types=1);

namespace PB\Cli\SmartBench\Tests\Fake\Model;

use PB\Cli\SmartBench\Model\{Book, BookCategory};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait GenerateModelTrait
{
    /**
     * @param int $id
     * @param string $name
     *
     * @return BookCategory
     *
     * @throws \ReflectionException
     */
    private function generateBookCategoryModel(int $id, string $name): BookCategory
    {
        $model = new BookCategory($name);

        $ref = new \ReflectionClass($model);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($model, $id);

        return $model;
    }

    /**
     * @param int $id
     * @param BookCategory $category
     * @param string $title
     * @param string $lang
     * @param string $author
     * @param int $year
     * @param float $price
     *
     * @return Book
     *
     * @throws \ReflectionException
     */
    private function generateBook(
        int $id,
        BookCategory $category,
        string $title,
        string $lang,
        string $author,
        int $year,
        float $price
    ) {
        $model = new Book($category, $title, $lang, $author, $year, $price);

        $ref = new \ReflectionClass($model);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($model, $id);

        return $model;
    }
}
