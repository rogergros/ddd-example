<?php

declare(strict_types=1);

namespace DDDExample\Domain\BowlingAlley;

interface BowlingAlleyRepository
{
    /**
     * @return list<BowlingAlley>
     */
    public function all(): array;

    public function save(BowlingAlley $bowlingAlley): void;
}
