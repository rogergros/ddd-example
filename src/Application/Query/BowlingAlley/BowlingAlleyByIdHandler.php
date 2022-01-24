<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

use DDDExample\Application\Query\QueryHandler;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;

final class BowlingAlleyByIdHandler implements QueryHandler
{
    public function __construct(
        private BowlingAlleyRepository $repository,
        private BowlingAlleyViewAssembler $assembler
    ) {
    }

    public function __invoke(BowlingAlleyById $query): BowlingAlleyView
    {
        return $this->assembler->assemble($this->repository->byId($query->id));
    }
}
