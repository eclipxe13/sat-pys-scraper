<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use LogicException;
use PhpCfdi\SatPysScraper\Exceptions\HttpException;
use PhpCfdi\SatPysScraper\Exceptions\HttpServerException;

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

    public function testObtainTypesThrowsExceptionOnServerError(): void
    {
        $scraper = $this->createPreparedScraperQueue([
            new Response(500, body: 'Internal server error'),
        ]);

        $this->expectException(HttpServerException::class);
        $scraper->obtainTypes();
    }

    public function testObtainTypesThrowsExceptionOnRequestError(): void
    {
        $scraper = $this->createPreparedScraperQueue([
            new Response(404, body: 'Not found'),
        ]);

        $this->expectException(HttpException::class);
        $scraper->obtainTypes();
    }
}
