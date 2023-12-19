<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\Data;

use PhpCfdi\SatPysScraper\Data\Segment;
use PhpCfdi\SatPysScraper\Data\Type;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class TypeTest extends TestCase
{
    /** @return array<int|string, Segment> */
    private function createUnsortedChildren(): array
    {
        $children = [
            new Segment(11, 'One'),
            new Segment(22, 'Two'),
            new Segment(33, 'Three'),
            new Segment(44, 'Four'),
        ];
        shuffle($children);
        return array_combine(array_column($children, 'id'), $children);
    }

    public function testProperties(): void
    {
        $id = 1;
        $name = 'foo';
        $children = $this->createUnsortedChildren();
        $parent = new Type($id, $name, $children);
        $this->assertSame($id, $parent->id);
        $this->assertSame($name, $parent->name);
        $this->assertSame($children, iterator_to_array($parent));
    }

    public function testSortByKey(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Type(1, 'foo', $children);
        $parent->sortByKey();
        $this->assertSame([11, 22, 33, 44], array_column(iterator_to_array($parent), 'id'));
    }

    public function testSortByName(): void
    {
        $children = $this->createUnsortedChildren();
        $parent = new Type(1, 'foo', $children);
        $parent->sortByName();
        $this->assertSame(['Four', 'One', 'Three', 'Two'], array_column(iterator_to_array($parent), 'name'));
    }
}
