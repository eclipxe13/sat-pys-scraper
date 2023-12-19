<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\Data;

use PhpCfdi\SatPysScraper\Data\Type;
use PhpCfdi\SatPysScraper\Data\Types;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class TypesTest extends TestCase
{
    /** @return array<int|string, Type> */
    private function createUnsortedChildren(): array
    {
        $children = [
            new Type(11, 'One'),
            new Type(22, 'Two'),
            new Type(33, 'Three'),
            new Type(44, 'Four'),
        ];
        shuffle($children);
        return array_combine(array_column($children, 'id'), $children);
    }

    public function testProperties(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Types($children);
        $this->assertSame($children, iterator_to_array($parent));
    }

    public function testSortByKey(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Types($children);
        $parent->sortByKey();
        $this->assertSame([11, 22, 33, 44], array_column(iterator_to_array($parent), 'id'));
    }

    public function testSortByName(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Types($children);
        $parent->sortByName();
        $this->assertSame(['Four', 'One', 'Three', 'Two'], array_column(iterator_to_array($parent), 'name'));
    }
}
