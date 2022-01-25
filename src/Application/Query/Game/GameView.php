<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

final class GameView
{
    /**
     * @param array<int,PlayerGameStateView> $playerGameStates
     */
    public function __construct(
        public string $id,
        public string $bowlingAlleyName,
        public string $bowlingAlleyId,
        public int $lane,
        public int $players,
        public int $frame,
        public ?int $nextPlayer,
        public bool $isFinished,
        public int $elapsedMinutes,
        public int $startedAtTimestamp,
        public array $playerGameStates
    ) {
    }
}
