<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

final class PlayerGameStateView
{
    /**
     * @param array<int,GameFrameStateView> $frames
     */
    public function __construct(
        public array $frames,
        public int $currentFrame,
        public bool $isFinished,
        public ?int $totalScore
    ) {
    }
}
