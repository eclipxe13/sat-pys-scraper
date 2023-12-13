<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Data;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/** @implements IteratorAggregate<int|string, Classification> */
final class Family implements IteratorAggregate, JsonSerializable
{
    /** @param array<int|string, Classification> $classes */
    public function __construct(
        public readonly int|string $id,
        public readonly string $name,
        private array $classes = [],
    ) {
    }

    public function addClass(int|string $id, string $name): Classification
    {
        $type = new Classification($id, $name);
        $this->classes[$id] = $type;
        return $type;
    }

    public function sortByKey(): void
    {
        ksort($this->classes);
    }

    public function sortByName(): void
    {
        usort($this->classes, fn (Classification $a, Classification $b): int => $a->name <=> $b->name);
    }

    /** @return Traversable<int|string, Classification> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->classes);
    }

    /** @return array{key: int|string, name: string, classes: list<Classification>} */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'classes' => array_values($this->classes),
        ];
    }
}
