<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

use DDDExample\Application\Query\QueryHandler;
use DDDExample\Domain\BowlingAlley\BowlingAlleyRepository;

final class AllBowlingAlleysHandler implements QueryHandler
{
    public function __construct(
        private BowlingAlleyRepository $repository,
        private BowlingAlleyViewAssembler $assembler
    ) {
    }

    /**
     * @return list<BowlingAlleyView>
     */
    public function __invoke(AllBowlingAlleys $query): array
    {
        return $this->assembler->assembleMultiple($this->repository->all());
    }
}
