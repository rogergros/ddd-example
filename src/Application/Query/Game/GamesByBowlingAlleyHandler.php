<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DDDExample\Application\Query\QueryHandler;
use DDDExample\Domain\Game\GameRepository;

final class GamesByBowlingAlleyHandler implements QueryHandler
{
    public function __construct(
        private GameRepository $repository,
        private GameViewAssembler $assembler
    ) {
    }

    /**
     * @return list<GameView>
     */
    public function __invoke(GamesByBowlingAlley $query): array
    {
        return $this->assembler->assembleMultiple($this->repository->byBowlingAlley($query->id));
    }
}
