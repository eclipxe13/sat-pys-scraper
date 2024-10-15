<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper\Tests\Unit\Exceptions;

use PhpCfdi\SatPysScraper\Exceptions\HttpException;
use PhpCfdi\SatPysScraper\Exceptions\HttpServerException;
use PhpCfdi\SatPysScraper\Tests\Unit\TestCase;

final class HttpServerExceptionTest extends TestCase
{
    public function testClassExtendsHttpException(): void
    {
        $exception = new HttpServerException('foo');
        $this->assertInstanceOf(HttpException::class, $exception);
    }
}
