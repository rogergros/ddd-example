<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\Game\Game;
use DDDExample\Domain\Game\GameRepository;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

final class BowlingAlleyViewAssembler
{
    public function __construct(
        private GameRepository $gameRepository
    ) {
    }

    public function assemble(BowlingAlley $bowlingAlley): BowlingAlleyView
    {
        $games = $this->gameRepository->byBowlingAlley($bowlingAlley->id());

        /** @var int $activeGames */
        $activeGames = reduce(
            fn(int $counter, Game $game): int => !$game->isFinished() ? $counter + 1 : $counter,
            $games,
            0
        );

        return new BowlingAlleyView(
            $bowlingAlley->id()->value(),
            $bowlingAlley->name()->value(),
            $bowlingAlley->lanes(),
            $activeGames
        );
    }

    /**
     * @param list<BowlingAlley> $bowlingAlleys
     * @return list<BowlingAlleyView>
     */
    public function assembleMultiple(array $bowlingAlleys): array
    {
        /** @var list<BowlingAlleyView> $assembledViews */
        $assembledViews = map(
            fn(BowlingAlley $bowlingAlley): BowlingAlleyView => $this->assemble($bowlingAlley),
            $bowlingAlleys
        );

        return $assembledViews;
    }
}
