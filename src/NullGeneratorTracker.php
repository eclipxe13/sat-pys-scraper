<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper;

class NullGeneratorTracker implements GeneratorTrackerInterface
{
    public function boot(): void
    {
    }

    public function type(Data\Type $type): void
    {
    }

    public function segment(Data\Segment $segment): void
    {
    }

    public function family(Data\Family $family): void
    {
    }

    public function class(Data\Classification $class): void
    {
    }
}
