<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper;

class NullGeneratorTracker implements GeneratorTrackerInterface
{
    public function boot(): void
    {
        // Null Object
    }

    public function type(Data\Type $type): void
    {
        // Null Object
    }

    public function segment(Data\Segment $segment): void
    {
        // Null Object
    }

    public function family(Data\Family $family): void
    {
        // Null Object
    }

    public function class(Data\Classification $class): void
    {
        // Null Object
    }
}
