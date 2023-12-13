<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Data;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/** @implements IteratorAggregate<int|string, Family> */
final class Segment implements IteratorAggregate, JsonSerializable
{
    /** @param array<int|string, Family> $families */
    public function __construct(
        public readonly int|string $id,
        public readonly string $name,
        private array $families = [],
    ) {
    }

    public function addFamily(int|string $id, string $name): Family
    {
        $type = new Family($id, $name);
        $this->families[$id] = $type;
        return $type;
    }

    public function sortByKey(): void
    {
        ksort($this->families);
        foreach ($this->families as $family) {
            $family->sortByKey();
        }
    }

    public function sortByName(): void
    {
        usort($this->families, fn (Family $a, Family $b): int => $a->name <=> $b->name);
        foreach ($this->families as $family) {
            $family->sortByName();
        }
    }

    /** @return Traversable<int|string, Family> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->families);
    }

    /** @return array{key: int|string, name: string, families: list<Family>} */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'families' => array_values($this->families),
        ];
    }
}
