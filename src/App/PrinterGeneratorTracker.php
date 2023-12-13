<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\App;

use PhpCfdi\SatPysScraper\Data\Family;
use PhpCfdi\SatPysScraper\Data\Segment;
use PhpCfdi\SatPysScraper\Data\Type;
use PhpCfdi\SatPysScraper\NullGeneratorTracker;

final class PrinterGeneratorTracker extends NullGeneratorTracker
{
    public function boot(): void
    {
        printf("Obteniendo tipos\n");
    }

    public function type(Type $type): void
    {
        printf("Obteniendo segmentos para el tipo %s - %s\n", $type->id, $type->name);
    }

    public function segment(Segment $segment): void
    {
        printf("\tObteniendo familias para el segmento %s - %s\n", $segment->id, $segment->name);
    }

    public function family(Family $family): void
    {
        printf("\t\tObteniendo clases para la familia %s - %s\n", $family->id, $family->name);
    }
}
