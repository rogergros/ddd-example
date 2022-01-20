<?php

declare(strict_types=1);

namespace DDDExample\Application\BowlingAlley;

use DDDExample\Application\Command;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\BowlingAlleyName;

class CreateBowlingAlley implements Command
{
    public function __construct(
        public BowlingAlleyId $id,
        public BowlingAlleyName $name,
        public int $lanes
    ) {
    }
}
