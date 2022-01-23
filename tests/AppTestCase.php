<?php

declare(strict_types=1);

namespace DDDExample\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AppTestCase extends TestCase
{
    private const DATETIME_SIMILAR_DELTA = 1;

    public static function assertDateTimeSimilar(
        DateTimeImmutable $expected,
        DateTimeImmutable $actual,
        string $message = ''
    ): void {
        $expectedTimestamp = $expected->getTimestamp();
        $actualTimestamp = $actual->getTimestamp();

        self::assertEqualsWithDelta($expectedTimestamp, $actualTimestamp, self::DATETIME_SIMILAR_DELTA, $message);
    }
}
