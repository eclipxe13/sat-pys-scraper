<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PhpCfdi\SatPysScraper\Scraper;
use PhpCfdi\SatPysScraper\ScraperInterface;
use PhpCfdi\SatPysScraper\Tests\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ScraperTest extends TestCase
{
    private const MAX_RETRIES = 5;

    private function createScraper(): ScraperInterface
    {
        $decider = fn (int $retries, RequestInterface $request, ResponseInterface $response = null): bool
            => $retries < self::MAX_RETRIES && null !== $response && $response->getStatusCode() >= 500;
        $delay = fn (int $retries): int => 1000 * ($retries + 1);

        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($decider, $delay));
        $client = new Client(['handler' => $stack]);

        return new Scraper($client);
    }

    public function testObtainSequence(): void
    {
        $scraper = $this->createScraper();

        $types = $scraper->obtainTypes();
        $expectedTypeId = 1;
        $expectedTypeText = 'Productos';
        $this->assertArrayHasKey($expectedTypeId, $types);
        $this->assertSame($expectedTypeText, $types[$expectedTypeId]);

        $segments = $scraper->obtainSegments($expectedTypeId);
        $expectedSegmentId = 50;
        $expectedSegmentText = 'Alimentos, Bebidas y Tabaco';
        $this->assertArrayHasKey($expectedSegmentId, $segments);
        $this->assertSame($expectedSegmentText, $segments[$expectedSegmentId]);

        $families = $scraper->obtainFamilies($expectedTypeId, $expectedSegmentId);
        $expectedFamilyId = 5015;
        $expectedFamilyText = 'Aceites y grasas comestibles';
        $this->assertArrayHasKey($expectedFamilyId, $families);
        $this->assertSame($expectedFamilyText, $families[$expectedFamilyId]);

        $classes = $scraper->obtainClasses($expectedTypeId, $expectedSegmentId, $expectedFamilyId);
        $expectedClassId = 501516;
        $expectedClassText = 'Grasas y aceites animales comestibles';
        $this->assertArrayHasKey($expectedClassId, $classes);
        $this->assertSame($expectedClassText, $classes[$expectedClassId]);
    }
}
