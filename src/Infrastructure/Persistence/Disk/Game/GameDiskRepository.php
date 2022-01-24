<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Persistence\Disk\Game;

use DDDExample\Domain\BowlingAlley\BowlingAlleyId;
use DDDExample\Domain\BowlingAlley\Exception\GameNotFound;
use DDDExample\Domain\Game\Game;
use DDDExample\Domain\Game\GameId;
use DDDExample\Domain\Game\GameRepository;
use DDDExample\Infrastructure\Persistence\Disk\DiskRepository;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\search;

/**
 * @extends DiskRepository<Game>
 */
class GameDiskRepository extends DiskRepository implements GameRepository
{
    public function save(Game $game): void
    {
        $this->data[] = $game;
        $this->saveDataOnFile();
    }

    /**
     * @return list<Game>
     */
    public function all(): array
    {
        return $this->data;
    }

    public function byId(GameId $id): Game
    {
        $game = search(
        /** @psalm-suppress ArgumentTypeCoercion */
            fn(Game $game) => $game->gameId()->eq($id),
            $this->all()
        );

        if (!$game instanceof Game) {
            throw GameNotFound::withId($id->value());
        }

        return $game;
    }

    /**
     * @return list<Game>
     */
    public function byBowlingAlley(BowlingAlleyId $id): array
    {
        /** @var list<Game> $filteredGames */
        $filteredGames = filter(
        /** @psalm-suppress ArgumentTypeCoercion */
            fn(Game $game): bool => $game->bowlingAlleyId()->eq($id),
            $this->all()
        );

        return $filteredGames;
    }

    protected function fileNamespace(): string
    {
        return 'game';
    }
}
