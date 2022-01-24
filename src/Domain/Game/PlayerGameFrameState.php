<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

class PlayerGameFrameState
{
    public static function create(): self
    {
        return new self();
    }

    public function __construct(
        private ?int $firstRoll = null,
        private ?int $secondRoll = null,
        private ?int $thirdRow = null,
        private ?int $score = null
    ) {
    }

    public function setFirstRoll(?int $knockedPins): void
    {
        $this->firstRoll = $knockedPins;
    }

    public function setSecondRoll(?int $knockedPins): void
    {
        $this->secondRoll = $knockedPins;
    }

    public function setThirdRoll(?int $knockedPins): void
    {
        $this->thirdRow = $knockedPins;
    }

    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    public function firstRoll(): ?int
    {
        return $this->firstRoll;
    }

    public function secondRoll(): ?int
    {
        return $this->secondRoll;
    }

    public function thirdRoll(): ?int
    {
        return $this->thirdRow;
    }

    public function score(): ?int
    {
        return $this->score;
    }
}
