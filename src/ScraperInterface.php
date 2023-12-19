<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper;

interface ScraperInterface
{
    /** @return array<int|string, string> */
    public function obtainTypes(): array;

    /** @return array<int|string, string> */
    public function obtainSegments(int|string $type): array;

    /** @return array<int|string, string> */
    public function obtainFamilies(int|string $type, int|string $segment): array;

    /** @return array<int|string, string> */
    public function obtainClasses(int|string $type, int|string $segment, int|string $family): array;
}
