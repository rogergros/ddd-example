<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DateTimeImmutable;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;
use DDDExample\Domain\Game\Game;

use function Lambdish\Phunctional\map;

final class GameViewAssembler
{
    public function __construct(
        private BowlingAlleyRepository $bowlingAlleyRepository
    ) {
    }

    public function assemble(Game $game): GameView
    {
        $bowlingAlley = $this->bowlingAlleyRepository->byId($game->bowlingAlleyId());

        return new GameView(
            $game->gameId()->value(),
            $bowlingAlley->name()->value(),
            $game->lane(),
            $game->numberOfPlayers(),
            $game->rolls(),
            $game->scoreboards(),
            $game->frame(),
            $game->nextPlayer(),
            $game->finishedAt() instanceof DateTimeImmutable,
            $game->minutesElapsed(),
            $game->startedAt()->getTimestamp()
        );
    }

    /**
     * @param list<Game> $games
     * @return list<GameView>
     */
    public function assembleMultiple(array $games): array
    {
        /** @var list<GameView> $assembledViews */
        $assembledViews = map(
            fn(Game $game): GameView => $this->assemble($game),
            $games
        );

        return $assembledViews;
    }
}
