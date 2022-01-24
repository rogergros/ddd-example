<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\Game;

use DDDExample\Application\Query\QueryHandler;
use DDDExample\Domain\Game\GameId;
use DDDExample\Domain\Game\GameRepository;

final class GameByIdHandler implements QueryHandler
{
    public function __construct(private GameRepository $repository, private GameViewAssembler $assembler)
    {
    }

    public function __invoke(GameById $query): GameView
    {
        return $this->assembler->assemble($this->repository->byId($query->id));
    }
}
