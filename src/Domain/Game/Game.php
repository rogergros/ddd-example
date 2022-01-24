<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use DateTimeImmutable;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfLanes;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfPlayers;
use DDDExample\Shared\Exception\RuntimeException;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\sort as fsort;

class Game
{
    private const MAX_LANES = 10;
    private const MAX_PLAYERS = 10;

    public static function create(
        GameId $gameId,
        BowlingAlleyId $bowlingAlleyId,
        int $lane,
        int $numberOfPlayers
    ): Game {
        if ($numberOfPlayers < 1 || $numberOfPlayers > self::MAX_PLAYERS) {
            throw new UnexpectedNumberOfPlayers($numberOfPlayers, self::MAX_PLAYERS);
        }

        /** @var PlayerGame[] $games */
        $games = map(fn(int $player) => PlayerGame::create($gameId, $player), range(1, $numberOfPlayers));

        return new self(
            $gameId,
            $bowlingAlleyId,
            $lane,
            $games,
            new DateTimeImmutable()
        );
    }

    /**
     * @param PlayerGame[] $playerGames
     */
    public function __construct(
        private GameId $gameId,
        private BowlingAlleyId $bowlingAlleyId,
        private int $lane,
        private array $playerGames,
        private DateTimeImmutable $startedAt,
        private ?DateTimeImmutable $finishedAt = null
    ) {
        if ($lane < 1 || $lane > self::MAX_LANES) {
            throw new UnexpectedNumberOfLanes($lane, self::MAX_LANES);
        }
    }

    /**
     * @return GameId
     */
    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function bowlingAlleyId(): BowlingAlleyId
    {
        return $this->bowlingAlleyId;
    }

    public function lane(): int
    {
        return $this->lane;
    }

    public function numberOfPlayers(): int
    {
        return count($this->playerGames);
    }

    public function startedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function finishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function minutesElapsed(): int
    {
        $intervalEnd = $this->finishedAt() ?? new DateTimeImmutable();
        $interval = $this->startedAt()->diff($intervalEnd);

        return ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
    }

    public function nextPlayer(): int
    {
        return $this->nextPlayerGame()->player();
    }

    /**
     * @return array<int,array<int,int|null>>
     */
    public function scoreboards(): array
    {
        $scores = [];
        foreach ($this->sortedPlayerGames() as $playerGame) {
            $scores[$playerGame->player()] = $playerGame->scoreboard();
        }

        return $scores;
    }

    /**
     * @return array<int,list<int>>
     */
    public function rolls()
    {
        $rolls = [];
        foreach ($this->sortedPlayerGames() as $playerGame) {
            $rolls[$playerGame->player()] = $playerGame->rolls();
        }

        return $rolls;
    }

    public function frame(): int
    {
        $minFrame = 10;
        foreach ($this->playerGames as $playerGame) {
            if ($playerGame->frame() < $minFrame) {
                $minFrame = $playerGame->frame();
            }
        }

        return $minFrame;
    }

    public function roll(int $knockedPins): void
    {
        $this->nextPlayerGame()->roll($knockedPins);

        $lastPlayerGame = $this->playerGame($this->numberOfPlayers());
        if ($lastPlayerGame->isFinished()) {
            $this->finishedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return PlayerGame[]
     */
    public function sortedPlayerGames(): array
    {
        /** @var PlayerGame[] $sortedPlayerGames */
        $sortedPlayerGames = fsort(
            fn(PlayerGame $gameOne, PlayerGame $gameTwo) => $gameOne->player() - $gameTwo->player(),
            $this->playerGames
        );

        return $sortedPlayerGames;
    }

    private function nextPlayerGame(): PlayerGame
    {
        $nextGame = null;
        foreach ($this->playerGames as $playerGame) {
            if (null === $nextGame) {
                $nextGame = $playerGame;
            } elseif (
                $playerGame->frame() < $nextGame->frame()
                || ($playerGame->frame() === $nextGame->frame() && $playerGame->player() < $nextGame->player())
            ) {
                $nextGame = $playerGame;
            }
        }

        if (!$nextGame instanceof PlayerGame) {
            throw new RuntimeException('No next player game found');
        }

        return $nextGame;
    }

    private function playerGame(int $player): PlayerGame
    {
        $sortedGames = $this->sortedPlayerGames();

        return $sortedGames[$player - 1];
    }
}
