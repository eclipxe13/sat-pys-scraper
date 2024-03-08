<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\App;

use PhpCfdi\SatPysScraper\App\PrinterGeneratorTracker;
use PhpCfdi\SatPysScraper\Data\Classification;
use PhpCfdi\SatPysScraper\Data\Family;
use PhpCfdi\SatPysScraper\Data\Segment;
use PhpCfdi\SatPysScraper\Data\Type;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class PrinterGeneratorTrackerTest extends TestCase
{
    public function testBoot(): void
    {
        $printer = new PrinterGeneratorTracker();

        $this->expectOutputRegex('/^Obteniendo tipos/');
        $printer->boot();
    }

    public function testType(): void
    {
        $printer = new PrinterGeneratorTracker();
        $type = new Type('1', 'Foo');

        $this->expectOutputRegex('/^Obteniendo segmentos para el tipo 1 - Foo/');
        $printer->type($type);
    }

    public function testSegment(): void
    {
        $printer = new PrinterGeneratorTracker();
        $segment = new Segment('99', 'Foo');

        $this->expectOutputRegex('/^\tObteniendo familias para el segmento 99 - Foo/');
        $printer->segment($segment);
    }

    public function testFamily(): void
    {
        $printer = new PrinterGeneratorTracker();
        $family = new Family('9999', 'Foo');

        $this->expectOutputRegex('/^\t\tObteniendo clases para la familia 9999 - Foo/');
        $printer->family($family);
    }

    public function testClass(): void
    {
        $printer = new PrinterGeneratorTracker();
        $classification = new Classification('999999', 'Foo');

        $this->expectOutputString('');
        $printer->class($classification);
    }
}
