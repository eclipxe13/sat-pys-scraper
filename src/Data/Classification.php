<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Data;

use JsonSerializable;

final class Classification implements JsonSerializable
{
    public function __construct(public readonly int|string $id, public readonly string $name)
    {
    }

    /** @return array{key: int|string, name: string} */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
        ];
    }
}
