<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit;

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
}
