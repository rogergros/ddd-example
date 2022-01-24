<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

class GameFrameStateView
{
    public function __construct(
        public ?int $firstRoll,
        public ?int $secondRoll,
        public ?int $thirdRoll,
        public ?int $score
    ) {
    }
}
