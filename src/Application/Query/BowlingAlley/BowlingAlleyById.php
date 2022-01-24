<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

use DDDExample\Application\Query\Query;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;

final class BowlingAlleyById implements Query
{
    public function __construct(
        public BowlingAlleyId $id
    ) {
    }
}
