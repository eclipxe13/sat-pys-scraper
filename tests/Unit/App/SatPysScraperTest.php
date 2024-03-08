<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\App;

use PhpCfdi\SatPysScraper\App\ArgumentException;
use PhpCfdi\SatPysScraper\App\SatPysScraper;
use PhpCfdi\SatPysScraper\ScraperInterface;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

final class SatPysScraperTest extends TestCase
{
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

    public function testProcessXmlOutputToFile(): void
    {
        $outputFile = '/tmp/result.xml';
        $arguments = ['-x', $outputFile];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => $outputFile,
            'json' => '',
            'quiet' => false,
            'sort' => 'key',
        ], $result);
    }

    public function testProcessJsonOutputToFile(): void
    {
        $outputFile = '/tmp/result.xml';
        $arguments = ['-j', $outputFile];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => '',
            'json' => $outputFile,
            'quiet' => false,
            'sort' => 'key',
        ], $result);
    }

    public function testProcessJsonOutputToStandard(): void
    {
        $arguments = ['--json', '-'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $result = $script->processArguments(...$arguments);

        $this->assertSame([
            'xml' => '',
            'json' => 'php://stdout',
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

    #[TestWith(['--help'])]
    #[TestWith(['-h'])]
    #[TestWith(['help'])]
    public function testHelp(string $helpArgument): void
    {
        $arguments = ['--first', $helpArgument, 'last'];
        $scraper = $this->createMock(ScraperInterface::class);
        $script = new SatPysScraper('command', $arguments, $scraper);

        $this->expectOutputRegex('/Crea un archivo XML con la clasificación de productos y servicios del SAT/');
        $script->execute();
    }

    public function testRunWithPreparedScraper(): void
    {
        $scraper = $this->createFakeScraper();
        $expectedXmlFile = __DIR__ . '/../../_files/exported-fake.xml';
        $xmlOutputFile = $this->createTemporaryFilename();

        $expectedJsonFile = __DIR__ . '/../../_files/exported-fake.json';
        $jsonOutputFile = $this->createTemporaryFilename();

        $argv = ['command', '--xml', $xmlOutputFile, '--json', $jsonOutputFile, '--quiet'];
        $script = new SatPysScraper('command', $argv, $scraper);

        $this->expectOutputString('');
        $result = $script->run(argv: $argv, scraper: $scraper);

        $this->assertXmlFileEqualsXmlFile($expectedXmlFile, $xmlOutputFile);
        $this->assertJsonFileEqualsJsonFile($expectedJsonFile, $jsonOutputFile);
        $this->assertSame(0, $result);
    }

    public function testRunWithError(): void
    {
        $scraper = $this->createFakeScraper();
        $argv = ['command'];
        $script = new SatPysScraper('command', $argv, $scraper);
        $stdErrFile = $this->createTemporaryFilename();

        $result = $script->run(argv: $argv, scraper: $scraper, stdErrFile: $stdErrFile);

        $this->assertSame(1, $result);
        $this->assertStringContainsString(
            'ERROR: Did not specify --xml or --json arguments',
            (string) file_get_contents($stdErrFile),
            'Expected error was not raised'
        );
    }
}
