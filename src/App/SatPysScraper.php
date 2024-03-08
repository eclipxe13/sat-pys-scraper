<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\App;

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

    /** @param string[] $argv */
    public static function run(
        array $argv,
        ScraperInterface $scraper = new Scraper(new Client()),
        string $stdErrFile = 'php://stderr'
    ): int {
        $command = (string) array_shift($argv);
        $argv = array_values($argv);
        $app = new self($command, $argv, $scraper);
        try {
            $app->execute();
            return 0;
        } catch (Throwable $exception) {
            file_put_contents($stdErrFile, 'ERROR: ' . $exception->getMessage() . PHP_EOL, FILE_APPEND);
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
                $command [--quiet|-q] [--json|-j JSON_FILE] [--xml|-x XML_FILE]

            Argumentos:
                --xml|-x XML_FILE
                    Establece el nombre de archivo, o "-" para la salida estándar, donde se envían
                    los datos generados en formato XML.
                --json|-j JSON_FILE
                    Establece el nombre de archivo, o "-" para la salida estándar, donde se envían
                    los datos generados en formato JSON.
                --sort|-s SORT
                    Establece el orden de elementos, default: key, se puede usar "key" o "name".
                --quiet|-q
                    Modo de operación silencioso.

            Notas:
                Debe especificar al menos un argumento "--xml" o "--json", o ambos.
                No se puede especificar "-" como salida de "--xml" y "--json" al mismo tiempo.

            Acerca de:
                Este script pertenece al proyecto https://github.com/phpcfdi/sat-pys-scraper
                y mantiene la autoría y licencia de todo el proyecto.


            HELP;
    }

    /** @throws ArgumentException */
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
            default => throw new ArgumentException('Unrecognized sort argument'),
        };

        if ('' !== $arguments['xml']) {
            $this->toXml($arguments['xml'], $types);
        }
        if ('' !== $arguments['json']) {
            $this->toJson($arguments['json'], $types);
        }
    }

    /**
     * @return array{xml: string, json: string, quiet: bool, sort: string}
     * @throws ArgumentException
     */
    public function processArguments(string ...$arguments): array
    {
        $arguments = array_values($arguments);
        $xml = '';
        $json = '';
        $quiet = false;
        $sort = 'key';

        $argumentsCount = count($arguments);
        for ($i = 0; $i < $argumentsCount; $i++) {
            $argument = $arguments[$i];
            if (in_array($argument, ['--xml', '-x'], true)) {
                $xml = strval($arguments[++$i] ?? '');
                continue;
            }
            if (in_array($argument, ['--json', '-j'], true)) {
                $json = strval($arguments[++$i] ?? '');
                continue;
            }
            if (in_array($argument, ['--sort', '-s'], true)) {
                $sort = strval($arguments[++$i] ?? '');
                if (! in_array($sort, ['key', 'name'])) {
                    throw new ArgumentException(sprintf('Invalid sort "%s"', $sort));
                }
                continue;
            }
            if (in_array($argument, ['--quiet', '-q'], true)) {
                $quiet = true;
                continue;
            }

            throw new ArgumentException(sprintf('Invalid argument "%s"', $argument));
        }

        if ('' === $xml && '' === $json) {
            throw new ArgumentException('Did not specify --xml or --json arguments');
        }
        if ('-' === $xml && '-' === $json) {
            throw new ArgumentException('Cannot send --xml and --json result to standard output at the same time');
        }
        if ('-' === $xml) {
            $xml = 'php://stdout';
            $quiet = true;
        }
        if ('-' === $json) {
            $json = 'php://stdout';
            $quiet = true;
        }

        return [
            'xml' => $xml,
            'json' => $json,
            'quiet' => $quiet,
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
