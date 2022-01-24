<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use DDDExample\Domain\BowlingAlley\BowlingAlleyId;

interface GameRepository
{
    public function save(Game $game): void;

    public function byId(GameId $id): Game;

    /**
     * @return list<Game>
     */
    public function byBowlingAlley(BowlingAlleyId $id): array;
}
