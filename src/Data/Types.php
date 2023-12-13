<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Data;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/** @implements IteratorAggregate<int|string, Type> */
final class Types implements IteratorAggregate, JsonSerializable
{
    /** @param array<int|string, Type> $types */
    public function __construct(private array $types = [])
    {
    }

    public function addType(int|string $id, string $name): Type
    {
        $type = new Type($id, $name);
        $this->types[$id] = $type;
        return $type;
    }

    public function sortByKey(): void
    {
        ksort($this->types);
        foreach ($this->types as $type) {
            $type->sortByKey();
        }
    }

    public function sortByName(): void
    {
        usort($this->types, fn (Type $a, Type $b): int => $a->name <=> $b->name);
        foreach ($this->types as $type) {
            $type->sortByName();
        }
    }

    /** @return Traversable<int|string, Type> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->types);
    }

    /** @return list<Type> */
    public function jsonSerialize(): array
    {
        return array_values($this->types);
    }
}
