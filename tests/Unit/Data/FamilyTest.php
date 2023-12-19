<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\Data;

use PhpCfdi\SatPysScraper\Data\Classification;
use PhpCfdi\SatPysScraper\Data\Family;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class FamilyTest extends TestCase
{
    /** @return array<int|string, Classification> */
    private function createUnsortedChildren(): array
    {
        $children = [
            new Classification(11, 'One'),
            new Classification(22, 'Two'),
            new Classification(33, 'Three'),
            new Classification(44, 'Four'),
        ];
        shuffle($children);
        return array_combine(array_column($children, 'id'), $children);
    }

    public function testProperties(): void
    {
        $id = 1;
        $name = 'foo';
        $children = $this->createUnsortedChildren();
        $parent = new Family($id, $name, $children);
        $this->assertSame($id, $parent->id);
        $this->assertSame($name, $parent->name);
        $this->assertSame($children, iterator_to_array($parent));
    }

    public function testSortByKey(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Family(1, 'foo', $children);
        $parent->sortByKey();
        $this->assertSame([11, 22, 33, 44], array_column(iterator_to_array($parent), 'id'));
    }

    public function testSortByName(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Family(1, 'foo', $children);
        $parent->sortByName();
        $this->assertSame(['Four', 'One', 'Three', 'Two'], array_column(iterator_to_array($parent), 'name'));
    }
}
