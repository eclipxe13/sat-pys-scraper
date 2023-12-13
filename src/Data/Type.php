<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Data;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/** @implements IteratorAggregate<int|string, Segment> */
final class Type implements IteratorAggregate, JsonSerializable
{
    /** @param array<int|string, Segment> $segments */
    public function __construct(
        public readonly int|string $id,
        public readonly string $name,
        private array $segments = [],
    ) {
    }

    public function addSegment(int|string $id, string $name): Segment
    {
        $type = new Segment($id, $name);
        $this->segments[$id] = $type;
        return $type;
    }

    public function sortByKey(): void
    {
        ksort($this->segments);
        foreach ($this->segments as $segment) {
            $segment->sortByKey();
        }
    }

    public function sortByName(): void
    {
        usort($this->segments, fn (Segment $a, Segment $b): int => $a->name <=> $b->name);
        foreach ($this->segments as $segment) {
            $segment->sortByName();
        }
    }

    /** @return Traversable<int|string, Segment> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->segments);
    }

    /** @return array{key: int|string, name: string, segments: list<Segment>} */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'segments' => array_values($this->segments),
        ];
    }
}
