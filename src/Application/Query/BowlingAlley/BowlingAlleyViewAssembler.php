<?php

declare(strict_types=1);

namespace DDDExample\Application\Query\BowlingAlley;

use DDDExample\Domain\BowlingAlley\BowlingAlley;

use function Lambdish\Phunctional\map;

final class BowlingAlleyViewAssembler
{
    public function assemble(BowlingAlley $bowlingAlley): BowlingAlleyView
    {
        return new BowlingAlleyView(
            $bowlingAlley->id()->value(),
            $bowlingAlley->name()->value(),
            $bowlingAlley->lanes()
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
