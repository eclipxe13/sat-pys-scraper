<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\App;

use Exception;
use GuzzleHttp\Client;
use PhpCfdi\SatPysScraper\Data\Types;
use PhpCfdi\SatPysScraper\Generator;
use PhpCfdi\SatPysScraper\NullGeneratorTracker;
use PhpCfdi\SatPysScraper\Scraper;
use PhpCfdi\SatPysScraper\ScraperInterface;
use PhpCfdi\SatPysScraper\XmlExporter;
use Throwable;

final readonly class SatPysScraper
{
    /**
     * @param list<string> $arguments
     */
    public function __construct(private string $command, private array $arguments, private ScraperInterface $scraper)
    {
    }

    public static function run(string $command, string ...$arguments): int
    {
        $app = new self($command, array_values($arguments), new Scraper(new Client()));
        try {
            $app->execute();
            return 0;
        } catch (Throwable $exception) {
            file_put_contents('php://stderr', 'ERROR: ' . $exception->getMessage() . PHP_EOL, FILE_APPEND);
            return 1;
        }
    }

    public function printHelp(): void
    {
        $command = basename($this->command);
        echo <<< HELP
            $command - Crea un archivo XML con la clasificación de productos y servicios del SAT.

            Sintaxis:
                $command help|-h|--help
                $command destination-file [--quiet|-q] [--format|-f FORMAT]

            Argumentos:
                destination-file
                    Nombre del archivo XML para almacenar el resultado.
                    Si se usa "-" o se omite entonces el resultado se manda a la salida estándar
                    y se activa el modo de operación silencioso.
                --format|-f FORMAT
                    Establece el formato de salida, default: xml, por el momento "xml" o "json".
                --sort|-s SORT
                    Establece el orden de elementos, default: key, se puede usar "key" o "name".
                --quiet|-q
                    Modo de operación silencioso.

            Acerca de:
                Este script pertenece al proyecto https://github.com/phpcfdi/sat-pys-scraper
                y mantiene la autoría y licencia de todo el proyecto.


            HELP;
    }

    /** @throws Exception */
    public function execute(): void
    {
        if ([] !== array_intersect($this->arguments, ['help', '-h', '--help'])) {
            $this->printHelp();
            return;
        }

        $arguments = $this->processArguments(...$this->arguments);
        $tracker = ($arguments['quiet']) ? new NullGeneratorTracker() : new PrinterGeneratorTracker();
        $types = (new Generator($this->scraper, $tracker))->generate();

        // sort types
        match ($arguments['sort']) {
            'key' => $types->sortByKey(),
            'name' => $types->sortByName(),
            default => throw new Exception('Unrecognized sort argument'),
        };

        // create output
        match ($arguments['format']) {
            'xml' => $this->toXml($arguments['output'], $types),
            'json' => $this->toJson($arguments['output'], $types),
            default => throw new Exception('Unrecognized format argument'),
        };
    }

    /**
     * @return array{output: string, quiet: bool, format: string, sort: string}
     * @throws Exception
     */
    public function processArguments(string ...$arguments): array
    {
        $arguments = array_values($arguments);
        $output = '';
        $quiet = false;
        $format = 'xml';
        $sort = 'key';

        $argumentsCount = count($arguments);
        for ($i = 0; $i < $argumentsCount; $i++) {
            $argument = $arguments[$i];
            if (in_array($argument, ['--format', '-f'], true)) {
                $format = strval($arguments[++$i] ?? '');
                if (! in_array($format, ['xml', 'json'])) {
                    throw new Exception(sprintf('Invalid format "%s"', $format));
                }
                continue;
            }
            if (in_array($argument, ['--sort', '-s'], true)) {
                $sort = strval($arguments[++$i] ?? '');
                if (! in_array($sort, ['key', 'name'])) {
                    throw new Exception(sprintf('Invalid sort "%s"', $sort));
                }
                continue;
            }
            if (in_array($argument, ['--quiet', '-q'], true)) {
                $quiet = true;
                continue;
            }
            if ('' === $output) {
                $output = $argument;
                continue;
            }

            throw new Exception(sprintf('Invalid argument "%s"', $argument));
        }

        if ('' === $output) {
            throw new Exception('Missing argument destination-file');
        }
        if ('-' === $output) {
            $output = 'php://stdout';
            $quiet = true;
        }

        return [
            'output' => $output,
            'quiet' => $quiet,
            'format' => $format,
            'sort' => $sort,
        ];
    }

    public function toXml(string $output, Types $types): void
    {
        $exporter = new XmlExporter();
        file_put_contents($output, (string) $exporter->exportAsDocument($types)->saveXML());
    }

    public function toJson(string $output, Types $types): void
    {
        file_put_contents($output, (string) json_encode($types, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
