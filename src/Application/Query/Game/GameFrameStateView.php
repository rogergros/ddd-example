<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

final class GameFrameStateView
{
    public string $firstRoll;
    public string $secondRoll;
    public string $thirdRoll;

    public function __construct(
        ?int $firstRoll,
        ?int $secondRoll,
        ?int $thirdRoll,
        public ?int $score
    ) {
        $this->firstRoll = $firstRoll === 10 ? 'X' : (string)$firstRoll;
        $this->thirdRoll = $thirdRoll === 10 ? 'X' : (string)$thirdRoll;

        if (null !== $firstRoll && null !== $secondRoll && 10 === ($firstRoll + $secondRoll)) {
            $this->secondRoll = '/';
        } elseif (10 === $secondRoll) {
            $this->secondRoll = 'X';
        } else {
            $this->secondRoll = (string)$secondRoll;
        }
    }
}
