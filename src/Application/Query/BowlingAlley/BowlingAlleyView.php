<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

final class BowlingAlleyView
{
    public function __construct(
        public string $id,
        public string $name,
        public int $lanes
    ) {
    }
}
