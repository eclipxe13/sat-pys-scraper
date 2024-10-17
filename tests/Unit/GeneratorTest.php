<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PhpCfdi\SatPysScraper\Exceptions\HttpException;
use PhpCfdi\SatPysScraper\Exceptions\HttpServerException;
use PhpCfdi\SatPysScraper\Generator;

class GeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $scraper = $this->createFakeScraper();
        $generator = new Generator($scraper);

        $types = $generator->generate();
        $types->sortByKey();

        $expectedFile = __DIR__ . '/../_files/exported-fake.json';
        $this->assertJsonStringEqualsJsonFile($expectedFile, (string) json_encode($types));
    }

    public function testGenerateThrowsExceptionOnServerError(): void
    {
        $scraper = $this->createPreparedScraperQueue([
            new Response(500, body: 'Internal server error'),
        ]);
        $generator = new Generator($scraper);

        $this->expectException(HttpServerException::class);
        $generator->generate();
    }

    public function testGenerateThrowsExceptionOnRequestError(): void
    {
        $scraper = $this->createPreparedScraperQueue([
            new Response(404, body: 'Not found'),
        ]);
        $generator = new Generator($scraper);

        $this->expectException(HttpException::class);
        $generator->generate();
    }
}
