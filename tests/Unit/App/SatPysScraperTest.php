<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\App;

use PhpCfdi\SatPysScraper\App\SatPysScraper;
use PhpCfdi\SatPysScraper\ScraperInterface;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class SatPysScraperTest extends TestCase
{
    public function testProcessArgumentsMinimal(): void
    {
        $arguments = ['output-file'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'output' => 'output-file',
            'quiet' => false,
            'format' => 'xml',
            'sort' => 'key',
        ], $result);
    }

    public function testProcessOutputToStandard(): void
    {
        $arguments = ['-'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'output' => 'php://stdout',
            'quiet' => true,
            'format' => 'xml',
            'sort' => 'key',
        ], $result);
    }

    public function testProcessArgumentsSetAll(): void
    {
        $arguments = ['--format', 'json', '--sort', 'name', '--quiet', 'output-file'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'output' => 'output-file',
            'quiet' => true,
            'format' => 'json',
            'sort' => 'name',
        ], $result);
    }

    public function testProcessArgumentsWithExtra(): void
    {
        $arguments = ['output-file', 'extra-argument'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectExceptionMessage('Invalid argument "extra-argument"');
        $script->processArguments(...$arguments);
    }

    public function testProcessArgumentsWithInvalidFormat(): void
    {
        $arguments = ['output-file', '--format', 'foo'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectExceptionMessage('Invalid format "foo"');
        $script->processArguments(...$arguments);
    }

    public function testProcessArgumentsWithInvalidSort(): void
    {
        $arguments = ['output-file', '--sort', 'foo'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectExceptionMessage('Invalid sort "foo"');
        $script->processArguments(...$arguments);
    }

    public function testProcessArgumentsWithoutOutput(): void
    {
        $arguments = [];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectExceptionMessage('Missing argument destination-file');
        $script->processArguments(...$arguments);
    }
}
