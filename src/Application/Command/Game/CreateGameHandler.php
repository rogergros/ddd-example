<?php

declare(strict_types=1);

namespace DDDExample\Application\Command\Game;

use DDDExample\Application\Command\CommandHandler;
use DDDExample\Domain\Game\Game;
use DDDExample\Domain\Game\GameRepository;

final class CreateGameHandler implements CommandHandler
{
    public function __construct(
        private GameRepository $repository
    ) {
    }

    public function __invoke(CreateGame $command): void
    {
        $game = Game::create(
            $command->gameId,
            $command->bowlingAlleyId,
            $command->lane,
            $command->numberOfPlayers
        );

        $this->repository->save($game);
    }
}
