<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Doctrine\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;

class BowlingAlleyDoctrineRepository implements BowlingAlleyRepository
{
    public function save(BowlingAlley $bowlingAlley): void
    {
        // TODO: Implement save() method.
    }
}
