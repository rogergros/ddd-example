<?php

declare(strict_types=1);

namespace DDDExample\Domain\Game;

use DateTimeImmutable;
use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\Game\Exception\GameAlreadyFinishedException;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfLanes;
use DDDExample\Domain\Game\Exception\UnexpectedNumberOfPlayers;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\sort as fsort;

final class Game
{
    private const MAX_LANES = 10;
    private const MAX_PLAYERS = 10;

    public static function create(
        GameId $gameId,
        BowlingAlleyId $bowlingAlleyId,
        int $lane,
        int $numberOfPlayers
    ): Game {
        self::guardNumberOfPlayers($numberOfPlayers);

        return new self(
            $gameId,
            $bowlingAlleyId,
            $lane,
            self::createPlayerGames($gameId, $numberOfPlayers),
            new DateTimeImmutable()
        );
    }

    /**
     * @param list<PlayerGame> $playerGames
     */
    public function __construct(
        private GameId $gameId,
        private BowlingAlleyId $bowlingAlleyId,
        private int $lane,
        private array $playerGames,
        private DateTimeImmutable $startedAt,
        private ?DateTimeImmutable $finishedAt = null
    ) {
        $this->guardNumberOfLanes($lane);
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

    public function isFinished(): bool
    {
        return $this->finishedAt instanceof DateTimeImmutable;
    }

    public function minutesElapsed(): int
    {
        $intervalEnd = $this->finishedAt ?? new DateTimeImmutable();
        $interval = $this->startedAt->diff($intervalEnd);

        return ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
    }

    public function nextPlayer(): ?int
    {
        $nextPlayerGame = $this->nextPlayerGame();

        return $nextPlayerGame instanceof PlayerGame ? $nextPlayerGame->player() : null;
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
        $nextPlayerGame = $this->nextPlayerGame();
        if (!$nextPlayerGame instanceof PlayerGame) {
            throw new GameAlreadyFinishedException();
        }

        $nextPlayerGame->roll($knockedPins);

        $lastPlayerGame = $this->playerGame($this->numberOfPlayers());
        if ($lastPlayerGame->isFinished()) {
            $this->finishedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return array<int,PlayerGameState>
     */
    public function playerGameStates(): array
    {
        $playerGameStates = [];
        foreach ($this->playerGames as $playerGame) {
            $playerGameStates[$playerGame->player()] = $playerGame->state();
        }

        return $playerGameStates;
    }

    /**
     * @return PlayerGame[]
     */
    private function sortedPlayerGames(): array
    {
        /** @var PlayerGame[] $sortedPlayerGames */
        $sortedPlayerGames = fsort(
            fn(PlayerGame $gameOne, PlayerGame $gameTwo) => $gameOne->player() - $gameTwo->player(),
            $this->playerGames
        );

        return $sortedPlayerGames;
    }

    private function nextPlayerGame(): ?PlayerGame
    {
        /** @var list<PlayerGame> $unfinishedGames */
        $unfinishedGames = filter(fn(PlayerGame $playerGame): bool => !$playerGame->isFinished(), $this->playerGames);

        $nextGame = null;
        foreach ($unfinishedGames as $playerGame) {
            if (null === $nextGame) {
                $nextGame = $playerGame;
            } elseif (
                $playerGame->frame() < $nextGame->frame()
                || ($playerGame->frame() === $nextGame->frame() && $playerGame->player() < $nextGame->player())
            ) {
                $nextGame = $playerGame;
            }
        }

        return $nextGame;
    }

    private function playerGame(int $player): PlayerGame
    {
        $sortedGames = $this->sortedPlayerGames();

        return $sortedGames[$player - 1];
    }

    private function guardNumberOfLanes(int $lane): void
    {
        if ($lane < 1 || $lane > self::MAX_LANES) {
            throw new UnexpectedNumberOfLanes($lane, self::MAX_LANES);
        }
    }

    private static function guardNumberOfPlayers(int $numberOfPlayers): void
    {
        if ($numberOfPlayers < 1 || $numberOfPlayers > self::MAX_PLAYERS) {
            throw new UnexpectedNumberOfPlayers($numberOfPlayers, self::MAX_PLAYERS);
        }
    }

    /**
     * @return list<PlayerGame>
     */
    private static function createPlayerGames(GameId $gameId, int $numberOfPlayers): array
    {
        /** @var list<PlayerGame> $playerGames */
        $playerGames = map(
            fn(int $player): PlayerGame => PlayerGame::create($gameId, $player),
            range(1, $numberOfPlayers)
        );

        return $playerGames;
    }
}
