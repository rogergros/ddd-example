<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use function Lambdish\Phunctional\map;

class PlayerGame
{
    private int $finishedFrames = 0;
    private bool $isFinished = false;
    /** @var int[] */
    private array $rolls = [];

    public static function create(
        GameId $gameId,
        int $player,
    ): PlayerGame {
        return new self($gameId, $player);
    }

    /**
     * @param int[] $rolls
     */
    public function __construct(
        private GameId $gameId,
        private int $player,
        array $rolls = []
    ) {
        if (!empty($rolls)) {
            map(fn(int $roll) => $this->roll($roll), $rolls);
        }
    }

    public function roll(int $knockedPins): void
    {
        $this->rolls[] = $knockedPins;

        // TODO: Update scoreboard on roll instead of recalculate it every time
        $scoreboard = $this->scoreboard();
        $this->finishedFrames = count(array_filter($scoreboard));
        $this->isFinished = 10 === $this->finishedFrames;
    }

    /**
     * @return array<int,?int>
     */
    public function scoreboard(): array
    {
        $scoreBoard = [null, null, null, null, null, null, null, null, null, null];
        $totalScore = 0;
        $roll = 0;

        for ($frame = 0; $frame < 10; ++$frame) {
            if ($this->frameIsStrike($roll)) {
                $frameScore = $this->getKnockedPins($roll, 3);
                $totalScore += $frameScore ?? 0;
                $scoreBoard[$frame] = $frameScore ? $totalScore : null;
                $roll += 1;
            } elseif ($this->frameIsSpare($roll)) {
                $frameScore = $this->getKnockedPins($roll, 3);
                $totalScore += $frameScore ?? 0;
                $scoreBoard[$frame] = $frameScore ? $totalScore : null;
                $roll += 2;
            } else {
                $frameScore = $this->getKnockedPins($roll, 2);
                $totalScore += $frameScore ?? 0;
                $scoreBoard[$frame] = $frameScore ? $totalScore : null;
                $roll += 2;
            }
        }

        return $scoreBoard;
    }

    public function frame(): int
    {
        return min(10, $this->finishedFrames + 1);
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function player(): int
    {
        return $this->player;
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
