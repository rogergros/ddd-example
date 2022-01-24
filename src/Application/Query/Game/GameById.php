<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DDDExample\Application\Query\Query;
use DDDExample\Domain\Game\GameId;

final class GameById implements Query
{
    public function __construct(
        public GameId $id
    ) {
    }
}
