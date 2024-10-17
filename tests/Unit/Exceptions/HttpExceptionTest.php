<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\Exceptions;

use PhpCfdi\SatPysScraper\Exceptions\HttpException;
use PhpCfdi\SatPysScraper\Exceptions\PysException;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class HttpExceptionTest extends TestCase
{
    public function testClassImplementsPysException(): void
    {
        $exception = new HttpException('foo');
        $this->assertInstanceOf(PysException::class, $exception);
    }
}
