<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DDDExample\Application\Query\Query;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;

final class GamesByBowlingAlley implements Query
{
    public function __construct(
        public BowlingAlleyId $id
    ) {
    }
}
