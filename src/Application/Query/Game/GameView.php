<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

final class GameView
{
    /**
     * @param array<int,list<int>> $rolls
     * @param array<int,array<int,int|null>> $scoreboards
     */
    public function __construct(
        public string $id,
        public string $bowlingAlleyName,
        public int $lane,
        public int $players,
        public array $rolls,
        public array $scoreboards,
        public int $frame,
        public int $nextPlayer,
        public bool $isFinished,
        public int $elapsedMinutes,
        public int $startedAtTimestamp
    ) {
    }
}
