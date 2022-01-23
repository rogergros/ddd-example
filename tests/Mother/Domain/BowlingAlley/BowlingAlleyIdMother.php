<?php

declare(strict_types=1);

namespace DDDExample\Tests\Mother\Domain\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use Symfony\Component\Uid\Ulid;

class BowlingAlleyIdMother
{
    public static function create(?string $value = null): BowlingAlleyId
    {
        return new BowlingAlleyId(
            $value ?? Ulid::generate()
        );
    }
}
