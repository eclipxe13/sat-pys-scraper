<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper;

interface ScraperInterface
{
    /**
     * @return array<int|string, string>
     * @throws Exceptions\HttpException|Exceptions\HttpServerException
     */
    public function obtainTypes(): array;

    /**
     * @return array<int|string, string>
     * @throws Exceptions\HttpException|Exceptions\HttpServerException
     */
    public function obtainSegments(int|string $type): array;

    /**
     * @return array<int|string, string>
     * @throws Exceptions\HttpException|Exceptions\HttpServerException
     */
    public function obtainFamilies(int|string $type, int|string $segment): array;

    /**
     * @return array<int|string, string>
     * @throws Exceptions\HttpException|Exceptions\HttpServerException
     */
    public function obtainClasses(int|string $type, int|string $segment, int|string $family): array;
}
