<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

final class PlayerGameState
{
    public static function create(): self
    {
        return new self(
            [
                1 => PlayerGameFrameState::create(),
                2 => PlayerGameFrameState::create(),
                3 => PlayerGameFrameState::create(),
                4 => PlayerGameFrameState::create(),
                5 => PlayerGameFrameState::create(),
                6 => PlayerGameFrameState::create(),
                7 => PlayerGameFrameState::create(),
                8 => PlayerGameFrameState::create(),
                9 => PlayerGameFrameState::create(),
                10 => PlayerGameFrameState::create(),
            ],
            1,
            false,
            0
        );
    }

    /**
     * @param array<int,PlayerGameFrameState> $frames
     */
    public function __construct(
        private array $frames,
        private int $currentFrame,
        private bool $isFinished,
        private int $totalScore
    ) {
    }

    /**
     * @return array<int,PlayerGameFrameState>
     */
    public function frames(): array
    {
        return $this->frames;
    }

    public function currentFrame(): int
    {
        return $this->currentFrame;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function totalScore(): ?int
    {
        return $this->totalScore;
    }

    public function frameScore(int $frame): ?int
    {
        return $this->frameState($frame)->score();
    }

    public function updateState(
        int $frame,
        ?int $frameFirstRoll,
        ?int $frameSecondRoll,
        ?int $frameThirdRoll,
        ?int $frameScore,
        int $totalScore,
    ): void {
        $frameState = $this->frameState($frame);
        $frameState->setFirstRoll($frameFirstRoll);
        $frameState->setSecondRoll($frameSecondRoll);
        $frameState->setThirdRoll($frameThirdRoll);
        $frameState->setScore($frameScore);
        $this->totalScore = $totalScore;
    }

    /**
     * @param int $totalScore
     */
    public function updateTotalScore(int $totalScore): void
    {
        $this->totalScore = $totalScore;
    }

    public function updateFrameFirstRoll(int $frame, ?int $knockedPins): void
    {
        $this->frameState($frame)->setFirstRoll($knockedPins);
    }

    public function updateFrameSecondRoll(int $frame, ?int $knockedPins): void
    {
        $this->frameState($frame)->setSecondRoll($knockedPins);
    }

    public function updateTenthFrameThirdRoll(?int $knockedPins): void
    {
        $this->frameState(10)->setThirdRoll($knockedPins);
    }

    public function updateFrameScore(int $frame, ?int $score): void
    {
        $this->frameState($frame)->setScore($score);
    }

    public function finish(): void
    {
        $this->isFinished = true;
    }

    public function nextFrame(): void
    {
        $this->currentFrame = min(10, $this->currentFrame + 1);
    }

    public function updateFrame(int $currentFrame): void
    {
        $this->currentFrame = $currentFrame;
    }

    private function frameState(int $frame): PlayerGameFrameState
    {
        return $this->frames[$frame];
    }
}
