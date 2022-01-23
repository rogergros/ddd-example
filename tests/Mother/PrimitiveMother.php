<?php

declare(strict_types=1);

namespace DDDExample\Tests\Mother;

use Faker\Factory;
use Faker\Generator;

class PrimitiveMother
{
    public const DEFAULT_LOCALE = 'en_US';

    private static ?Generator $faker = null;

    public static function int(int $min = 0, int $max = 10000): int
    {
        return self::generator()->numberBetween($min, $max);
    }

    private static function generator(): Generator
    {
        return self::$faker = self::$faker ?? Factory::create(self::DEFAULT_LOCALE);
    }
}
