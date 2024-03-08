<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\App;

use PhpCfdi\SatPysScraper\App\SatPysScraper;
use PhpCfdi\SatPysScraper\ScraperInterface;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

final class SatPysScraperTest extends TestCase
{
    public function testProcessArgumentsMinimal(): void
    {
        $arguments = ['--xml', '-'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => 'php://stdout',
            'json' => '',
            'quiet' => true,
            'sort' => 'key',
        ], $result);
    }

    public function testProcessXmlOutputToStandard(): void
    {
        $arguments = ['--xml', '-'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => 'php://stdout',
            'json' => '',
            'quiet' => true,
            'sort' => 'key',
        ], $result);
    }

    public function testProcessXmlArgumentsSetAll(): void
    {
        $arguments = ['--xml', 'result.xml', '--json', 'result.json', '--sort', 'name', '--quiet'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => 'result.xml',
            'json' => 'result.json',
            'quiet' => true,
            'sort' => 'name',
        ], $result);
    }

    public function testProcessWithoutArguments(): void
    {
        $arguments = [];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Did not specify --xml or --json arguments');
        $script->processArguments(...$arguments);
    }

    public function testProcessWithXmlAndJsonOutputToStdout(): void
    {
        $arguments = ['-x', '-', '-j', '-'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Cannot send --xml and --json result to standard output at the same time');
        $script->processArguments(...$arguments);
    }

    public function testProcessArgumentsWithExtra(): void
    {
        $arguments = ['extra-argument'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Invalid argument "extra-argument"');
        $script->processArguments(...$arguments);
    }

    public function testProcessArgumentsWithInvalidSort(): void
    {
        $arguments = ['--sort', 'foo'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Invalid sort "foo"');
        $script->processArguments(...$arguments);
    }

    #[TestWith(['--xml'])]
    #[TestWith(['--json'])]
    public function testProcessArgumentsWithoutOutput(string $format): void
    {
        $arguments = [$format, ''];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Did not specify --xml or --json arguments');
        $script->processArguments(...$arguments);
    }
}
