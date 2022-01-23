<?php

declare(strict_types=1);

namespace DDDExample\Application\Command\BowlingAlley;

use DDDExample\Application\Command\CommandHandler;
use DDDExample\Domain\BowlingAlley\BowlingAlley;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;

class CreateBowlingAlleyHandler implements CommandHandler
{
    public function __construct(private BowlingAlleyRepository $repository)
    {
    }

    public function __invoke(CreateBowlingAlley $command): void
    {
        $bowlingAlley = BowlingAlley::create($command->id, $command->name, $command->lanes);

        $this->repository->save($bowlingAlley);
    }
}
