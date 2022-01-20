<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley;

interface BowlingAlleyRepository
{
    public function save(BowlingAlley $bowlingAlley): void;
}
