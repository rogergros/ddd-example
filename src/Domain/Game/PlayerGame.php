<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use DDDExample\Domain\Game\Exception\UnexpectedNumberOfKnockedPins;

final class PlayerGame
{
    public static function create(
        GameId $gameId,
        int $player,
    ): PlayerGame {
        return new self($gameId, $player, [], PlayerGameState::create());
    }

    /**
     * @param list<int> $rolls
     */
    public function __construct(
        private GameId $gameId,
        private int $player,
        private array $rolls,
        private PlayerGameState $state
    ) {
        $this->updateGameStats();
    }

    public function roll(int $knockedPins): void
    {
        $this->rolls[] = $knockedPins;

        $this->updateGameStats();
    }

    /**
     * @return list<int>
     */
    public function rolls(): array
    {
        return $this->rolls;
    }

    public function frame(): int
    {
        return $this->state->currentFrame();
    }

    public function isFinished(): bool
    {
        return $this->state->isFinished();
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function player(): int
    {
        return $this->player;
    }

    public function totalScore(): ?int
    {
        return $this->state->totalScore();
    }

    public function state(): PlayerGameState
    {
        return $this->state;
    }

    private function updateGameStats(): void
    {
        $this->state = PlayerGameState::create();

        $totalScore = 0;
        $roll = 0;
        for ($frame = 1; $frame <= 10; ++$frame) {
            if ($this->frameIsStrike($roll)) {
                $frameScore = $this->getKnockedPins($roll, 3);
                $totalScore += $frameScore ?? 0;
                $firsRoll = 10 === $frame ? 10 : null;
                $secondRoll = 10 === $frame ? ($this->rolls[$roll + 1] ?? null) : 10;
                $thirdRoll = 10 === $frame ? ($this->rolls[$roll + 2] ?? null) : null;

                $roll += 1;
            } elseif ($this->frameIsSpare($roll)) {
                $frameScore = $this->getKnockedPins($roll, 3);
                $totalScore += $frameScore ?? 0;
                $firsRoll = $this->rolls[$roll] ?? null;
                $secondRoll = $this->rolls[$roll + 1] ?? null;
                $thirdRoll = 10 === $frame ? ($this->rolls[$roll + 2] ?? null) : null;

                $roll += 2;
            } else {
                $frameScore = $this->getKnockedPins($roll, 2);
                if ($frameScore !== null && $frameScore > 10) {
                    throw new UnexpectedNumberOfKnockedPins($frameScore);
                }

                $totalScore += $frameScore ?? 0;
                $firsRoll = $this->rolls[$roll] ?? null;
                $secondRoll = $this->rolls[$roll + 1] ?? null;
                $thirdRoll = 10 === $frame ? ($this->rolls[$roll + 2] ?? null) : null;

                $roll += 2;
            }

            $this->state->updateState(
                $frame,
                $firsRoll,
                $secondRoll,
                $thirdRoll,
                $frameScore ? $totalScore : null,
                $totalScore
            );

            if (null !== $secondRoll) {
                $this->state->nextFrame();
            }
        }

        if (null !== $this->state->frameScore(10)) {
            $this->state->finish();
        }
    }

    private function frameIsStrike(int $frameRollIndex): bool
    {
        return
            isset($this->rolls[$frameRollIndex])
            && $this->rolls[$frameRollIndex] === 10;
    }

    private function frameIsSpare(int $frameRollIndex): bool
    {
        return
            isset($this->rolls[$frameRollIndex], $this->rolls[$frameRollIndex + 1])
            && ($this->rolls[$frameRollIndex] + $this->rolls[$frameRollIndex + 1]) === 10;
    }

    private function getKnockedPins(int $rollIndex, int $rollAmount): ?int
    {
        $knockedPins = 0;
        for ($i = $rollAmount - 1; $i >= 0; --$i) {
            if (!isset($this->rolls[$rollIndex + $i])) {
                return null;
            }
            $knockedPins += $this->rolls[$rollIndex + $i] ?? 0;
        }

        return $knockedPins;
    }
}
