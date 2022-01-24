<?php

declare(strict_types=1);

namespace DDDExample\Application\Command\Game;

use DDDExample\Application\Command\Command;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\Game\GameId;

class CreateGame implements Command
{
    public function __construct(
        public GameId $gameId,
        public BowlingAlleyId $bowlingAlleyId,
        public int $lane,
        public int $numberOfPlayers
    ) {
    }
}
