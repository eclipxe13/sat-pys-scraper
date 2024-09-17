<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit;

use LogicException;

final class ScraperTest extends TestCase
{
    public function testCallObtainSegmentsWithoutCorrectSequenceThrowsLogicException(): void
    {
        $scraper = $this->createFakeScraper();
        $this->expectException(LogicException::class);
        $scraper->obtainSegments(1);
    }

    public function testCallObtainFamiliesWithoutCorrectSequenceThrowsLogicException(): void
    {
        $scraper = $this->createFakeScraper();
        $this->expectException(LogicException::class);
        $scraper->obtainFamilies(27, 1);
    }

    public function testCallObtainClassesWithoutCorrectSequenceThrowsLogicException(): void
    {
        $scraper = $this->createFakeScraper();
        $this->expectException(LogicException::class);
        $scraper->obtainClasses(2711, 27, 1);
    }
}
