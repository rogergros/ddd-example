<?php

declare(strict_types=1);

namespace DDDExample\Application\Command\Game;

use DDDExample\Application\Command\CommandHandler;
use DDDExample\Domain\Game\GameRepository;

final class GameRollHandler implements CommandHandler
{
    public function __construct(
        private GameRepository $repository
    ) {
    }

    public function __invoke(GameRoll $command): void
    {
        $game = $this->repository->byId($command->id);

        $game->roll($command->knockedPins);

        $this->repository->save($game);
    }
}
