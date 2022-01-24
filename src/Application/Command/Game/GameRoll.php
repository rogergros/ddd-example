<?php

declare(strict_types=1);

namespace DDDExample\Application\Command\Game;

use DDDExample\Application\Command\Command;
use DDDExample\Domain\Game\GameId;

final class GameRoll implements Command
{
    public function __construct(
        public GameId $id,
        public int $knockedPins
    ) {
    }
}
